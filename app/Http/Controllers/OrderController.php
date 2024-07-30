<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->user_type === 'admin') {
            $orders = Order::all();  // Fetch all orders for admin
            return view('orders.index', ['view' => 'admin', 'orders' => $orders]);
        } else {
            $orders = Order::where('user_id', $user->id)->get(); // Fetch only orders for the logged-in user
            return view('orders.index', ['view' => 'general', 'orders' => $orders]);
        }
    }

    public function show($id)
    {
        $orders = Order::findOrFail($id);
        return view('orders.show', compact('orders'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'product' => 'required|string',
            'quantity' => 'required|integer',
            'size' => 'required|string',
            'total_paid' => 'required|numeric',
            'payment_id' => 'required|string',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'type' => 'required|string'
        ]);

        $order = Order::create($validatedData);
        return redirect()->route('order.success'); // Ensure this route is defined
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
