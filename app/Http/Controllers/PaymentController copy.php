<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Event;
use App\Models\Stocks;
use App\Models\Membership;
use App\Models\Ticket;
use App\Mail\ReceiptMail;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller {
    private $gateway;

    public function __construct() {
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        Log::info('PayPal Client ID: ' . $clientId);
        Log::info('PayPal Client Secret: ' . $clientSecret);
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($clientId);
        $this->gateway->setSecret($clientSecret);
        $this->gateway->setTestMode(true);
    }

    public function pay(Request $request) {
        Log::info('Entered pay method');
        
        try {
            Log::info('Request data', ['request' => $request->all()]);

            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required',
                'street' => 'required|string',
                'city' => 'required|string',
                'postcode' => 'required|string',
                'state' => 'required|string',
                'amount' => 'required|numeric',
            ]);

            $cart = session('cart');
            $eventId = null;
            foreach ($cart as $item) {
                if (isset($item['event_id'])) {
                    $eventId = $item['event_id'];
                    break;
                }
            }

            Log::info('Validation passed', ['validated' => $validated]);

            session([
                'delivery_details' => [
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'street' => $request->input('street'),
                    'city' => $request->input('city'),
                    'postcode' => $request->input('postcode'),
                    'state' => $request->input('state'),
                    'event_id' => $eventId,
                ]
            ]);

            Log::info('Session data stored', ['session' => session('delivery_details')]);

            $user = Auth::user();
            $originalTotal = 0;
            $eligibleProductTotal = 0;

            Log::info('Calculating totals', ['cart' => $cart]);

            foreach ($cart as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $originalTotal += $itemTotal;
                if (isset($item['product_id'])) {
                    $eligibleProductTotal += $itemTotal;
                }
            }

            $totalPaid = $originalTotal;
            if ($user && $user->membership && $eligibleProductTotal > 0) {
                $totalPaid -= $eligibleProductTotal * 0.1;
            }

            Log::info('Total amount to be paid: ' . $totalPaid);

            $response = $this->gateway->purchase([
                'amount' => $totalPaid,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.error')
            ])->send();

            if ($response->isRedirect()) {
                Log::info('Redirecting to PayPal for payment.');
                return $response->redirect();
            } else {
                Log::error('PayPal purchase response error: ' . $response->getMessage());
                return redirect('/cart')->withErrors('Error initiating PayPal payment: ' . $response->getMessage());
            }
        } catch (ValidationException $e) {
            Log::error('Validation failed: ', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors());
        } catch (\Throwable $th) {
            Log::error("Error during payment initiation: " . $th->getMessage(), ['exception' => $th]);
            return back()->withErrors('Error initiating payment: ' . $th->getMessage());
        }
    }

    public function success(Request $request) {
        Log::info("Entered success method");
        $paymentId = $request->query('paymentId');
        $payerId = $request->query('PayerID');
    
        if (!$paymentId || !$payerId) {
            Log::error("Payment or Payer ID is missing.");
            return redirect('/cart')->withErrors("Payment unsuccessful: Missing important payment data.");
        }
    
        try {
            $transaction = $this->gateway->completePurchase([
                'payerId' => $payerId,
                'transactionReference' => $paymentId,
            ])->send();
    
            $data = $transaction->getData();
            Log::info('Transaction Data: ' . json_encode($data));
    
            if (!$transaction->isSuccessful()) {
                Log::error("Transaction failed: " . $transaction->getMessage());
                return redirect('/cart')->withErrors("Transaction failed: " . $transaction->getMessage());
            }
    
            if (!isset($data['id'], $data['payer']['payer_info']['payer_id'], $data['payer']['payer_info']['email'], $data['transactions'][0]['amount']['total'], $data['transactions'][0]['amount']['currency'], $data['state'])) {
                Log::error('Transaction data structure is incorrect.');
                return redirect('/cart')->withErrors('Transaction data structure is incorrect.');
            }
    
            $paymentData = [
                'payment_id' => $data['id'],
                'payer_id' => $data['payer']['payer_info']['payer_id'],
                'payer_email' => $data['payer']['payer_info']['email'],
                'amount' => $data['transactions'][0]['amount']['total'],
                'currency' => $data['transactions'][0]['amount']['currency'],
                'payment_status' => $data['state'],
            ];
    
            Log::info('Payment Data: ' . json_encode($paymentData));
    
            DB::beginTransaction();
            try {
                $payment = Payment::create($paymentData);
                DB::commit();
                Log::info('Payment record created successfully: ' . $payment->id);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error creating Payment record: " . $e->getMessage());
                return redirect('/cart')->withErrors("Payment creation failed: " . $e->getMessage());
            }
    
            $deliveryDetails = session('delivery_details');
            $fullAddress = $deliveryDetails['street'] . ', ' . $deliveryDetails['city'] . ', ' . $deliveryDetails['postcode'] . ', ' . $deliveryDetails['state'];
            $productNames = collect(session('cart'))->pluck('name')->join(', ');
            $types = $this->determineItemTypes(session('cart'));
    
            $orderData = [
                'user_id' => Auth::id(),
                'name' => $deliveryDetails['name'],
                'phone_number' => $deliveryDetails['phone'],
                'email' => $deliveryDetails['email'],
                'address' => $fullAddress,
                'product' => $productNames,
                'type' => implode(', ', $types),
                'quantity' => collect(session('cart'))->pluck('quantity')->join(', '),
                'size' => collect(session('cart'))->pluck('size')->join(', '),
                'total_paid' => $payment->amount,
                'payment_id' => $payment->id,
                'status' => 'Completed',
                'payment_status' => 'Paid',
            ];
    
            Log::info('Order Data: ' . json_encode($orderData));
    
            try {
                $order = Order::create($orderData);
                Log::info('Order created successfully with ID: ' . $order->id);
            } catch (\Exception $e) {
                Log::error("Error creating Order record: " . $e->getMessage());
                return redirect('/cart')->withErrors("Order creation failed: " . $e->getMessage());
            }
    
            if ($order) {
                Log::info('Order created successfully with ID: ' . $order->id);
                $this->deductStocks(session('cart'));
                session()->forget('cart');
    
                $ticket = null;
                if ($eventId = $deliveryDetails['event_id']) {
                    Log::info("Event ID: " . $eventId);
    
                    $event = Event::find($eventId);
                    if (!$event) {
                        Log::error("Event not found.");
                        return redirect('/cart')->withErrors("Event not found.");
                    }
    
                    $ticketPath = $this->generateTicket($event, $order);
    
                    $ticketData = [
                        'user_id' => Auth::id(),
                        'event_id' => $event->id,
                        'ticket_path' => $ticketPath,
                    ];
    
                    Log::info('Ticket Data: ' . json_encode($ticketData));
    
                    try {
                        $ticket = Ticket::create($ticketData);
                        Log::info('Ticket created successfully with ID: ' . $ticket->id);
                    } catch (\Exception $e) {
                        Log::error("Error creating Ticket record: " . $e->getMessage());
                        return redirect('/cart')->withErrors("Ticket creation failed: " . $e->getMessage());
                    }
    
                    Mail::to($order->email)->send(new TicketMail($ticket));
                }
    
                if ($productNames) {
                    Mail::to($order->email)->send(new ReceiptMail($order));
                }
    
                Log::info('Redirecting to receipt.show with payment ID: ' . $payment->id);
                return redirect()->route('receipt.show', ['id' => $payment->id]);
            } else {
                Log::error("Order creation failed. Data: " . json_encode($orderData));
                return redirect('/cart')->withErrors("Order creation failed.");
            }
    
        } catch (\Throwable $th) {
            Log::error("Error processing payment: " . $th->getMessage(), ['exception' => $th]);
            return redirect('/cart')->withErrors("Error processing payment: " . $th->getMessage());
        }
    }

    public function showReceipt($payment_id) {
        try {
            // Find the payment record
            $payment = Payment::findOrFail($payment_id);
            
            // Find the order associated with this payment
            $order = Order::where('payment_id', $payment_id)->firstOrFail();
            
            // Fetch user and membership details
            $user = Auth::user();
            $membership = $user ? $user->membership : null;
            
            // Parse product names, quantities, and sizes from the order
            $productNames = explode(', ', $order->product);
            $quantities = explode(', ', $order->quantity);
            $sizes = explode(', ', $order->size);
            $totalPaid = $order->total_paid;
    
            // Prepare order details for display
            $orderDetails = [];
            foreach ($productNames as $index => $productName) {
                $orderDetails[] = [
                    'name' => $productName,
                    'quantity' => $quantities[$index] ?? 'N/A',
                    'size' => $sizes[$index] ?? 'N/A',
                ];
            }
    
            // Find the ticket associated with this order
            $ticket = Ticket::where('user_id', $order->user_id)->where('event_id', $deliveryDetails['event_id'])->first();
    
            // Render the receipt view
            return view('receipt', compact('payment', 'membership', 'order', 'orderDetails', 'totalPaid', 'ticket'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Payment or Order not found for Payment ID: " . $payment_id);
            abort(404, 'Payment or Order not found.');
        }
    }       

    private function determineItemTypes($cartItems) {
        $types = [];
        foreach ($cartItems as $item) {
            if (isset($item['event_id'])) {
                $types[] = 'Event';
            } elseif (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                $types[] = $product->category->name ?? 'Product';
            }
        }
        return $types;
    }

    private function deductStocks($cartItems) {
        foreach ($cartItems as $item) {
            if (isset($item['product_id'], $item['size'], $item['quantity'])) {
                $this->deductStock($item['product_id'], $item['size'], $item['quantity']);
            } elseif (isset($item['event_id'], $item['quantity'])) {
                $this->deductEventStock($item['event_id'], $item['quantity']);
            }
        }
    }

    private function deductEventStock($eventId, $quantity) {
        $event = Event::find($eventId);
        if ($event && $event->ticket_stock >= $quantity) {
            $event->ticket_stock -= $quantity;
            $event->save();
        } else {
            Log::error("Not enough tickets or event not found for Event ID: $eventId");
        }
    }

    private function deductStock($productId, $size, $quantity) {
        $stock = Stocks::where('product_id', $productId)->where('size', $size)->first();
        if ($stock) {
            $stock->quantity -= $quantity;
            $stock->save();
        } else {
            Log::error("Stock not found for product ID: $productId and size: $size");
        }
    }

    public function generateTicket($event, $order) {
        if (!$event || !$order) {
            throw new \Exception("Invalid event or order.");
        }
    
        // Generate QR code using the Endroid\QrCode library
        $qrCode = new QrCode('Event: ' . $event->event . ', Date: ' . $event->date . ', Location: ' . $event->venue);
        $qrCode->setSize(100);
        $qrCodeImage = $qrCode->writeString();
        $qrCodeImage = base64_encode($qrCodeImage);
    
        // Create the ticket image
        $ticket = Image::canvas(400, 200, '#ffffff');
    
        // Add brand logo
        $logo = Image::make(public_path('build/images/F_black.png'))->resize(50, 50);
        $ticket->insert($logo, 'top-left', 10, 10);
    
        // Add event details
        $ticket->text('Event: ' . $event->event, 70, 20, function ($font) {
            $font->size(20);
            $font->color('#000000');
        });
        $ticket->text('Location: ' . $event->venue, 70, 60, function ($font) {
            $font->size(15);
            $font->color('#000000');
        });
        $ticket->text('Date: ' . $event->date, 70, 100, function ($font) {
            $font->size(15);
            $font->color('#000000');
        });
    
        // Add QR code
        $qrCode = Image::make(base64_decode($qrCodeImage))->resize(100, 100);
        $ticket->insert($qrCode, 'bottom-right', 10, 10);
    
        // Save the ticket image
        $ticketPath = 'photos/ticket_' . $order->id . '.png';
        Storage::disk('public')->put($ticketPath, (string) $ticket->encode());
    
        return $ticketPath;
    }        

    public function error() {
        return "User declined the payment!";
    }

    public function testOmnipay() {
        try {
            $gateway = Omnipay::create('PayPal_Rest');
            $gateway->setClientId(env('PAYPAL_CLIENT_ID'));
            $gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
            $gateway->setTestMode(true);

            $response = $gateway->purchase([
                'amount' => '10.00',
                'currency' => 'USD',
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.error')
            ])->send();

            if ($response->isRedirect()) {
                Log::info('Test: Redirecting to PayPal for payment.');
                return $response->redirect();
            } else {
                Log::error('Test: PayPal purchase response error: ' . $response->getMessage());
                return redirect('/cart')->withErrors('Test: Error initiating PayPal payment: ' . $response->getMessage());
            }
        } catch (\Throwable $th) {
            Log::error("Test: Error during payment initiation: " . $th->getMessage(), ['exception' => $th]);
            return back()->withErrors('Test: Error initiating payment: ' . $th->getMessage());
        }
    }
}
