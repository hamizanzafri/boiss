<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Event;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $productTotal = 0;

        foreach ($cart as $item) {
            $totalItem = $item['price'] * $item['quantity'];
            $total += $totalItem;

            if (isset($item['size'])) {
                $productTotal += $totalItem;
            }
        }

        return view('cart', [
            'cart' => $cart, 
            'total' => $total, 
            'productTotal' => $productTotal
        ]);
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $quantity = $request->quantity ?? 1;

        if ($request->has('size')) {
            $product = Product::find($id);
            if (!$product) {
                \Log::error("Attempted to add non-existent product with ID {$id}");
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $size = $request->size;
            $cartItemKey = $id . '-' . $size;

            if (isset($cart[$cartItemKey])) {
                $cart[$cartItemKey]['quantity'] += $quantity;
            } else {
                $cart[$cartItemKey] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'size' => $size,
                    'quantity' => $quantity,
                    'product_id' => $product->id // Ensure product_id is included
                ];
            }
        } else {
            $event = Event::find($id);
            if (!$event) {
                \Log::error("Attempted to add non-existent event with ID {$id}");
                return response()->json(['success' => false, 'message' => 'Event not found'], 404);
            }

            $cartItemKey = 'event-' . $id;
            if (isset($cart[$cartItemKey])) {
                $cart[$cartItemKey]['quantity'] += $quantity;
            } else {
                $cart[$cartItemKey] = [
                    'name' => $event->event,
                    'price' => $event->ticket_price,
                    'quantity' => $quantity,
                    'event_id' => $event->id // Ensure event_id is included
                ];
            }
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'message' => 'Item added to cart successfully']);
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true, 'message' => 'Quantity updated successfully']);
        }
        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json(['success' => true, 'message' => 'Item removed successfully']);
        }
        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    public function debugSession(Request $request)
    {
        $cart = session()->get('cart', []);
        return response()->json($cart);
    }
}
