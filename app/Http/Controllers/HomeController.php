<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Event;
use App\Models\Order;
use App\Models\Membership;
use App\Models\Voucher;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Fetch top-selling products based on order quantity
        $topSellingProducts = Order::select('product', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product')
            ->orderBy('total_quantity', 'desc')
            ->take(6)
            ->pluck('product');

        // Ensure product names are unique and not empty
        $uniqueProducts = array_filter($topSellingProducts->toArray());

        // Fetch product details for the top-selling products
        $products = Product::whereIn('name', $uniqueProducts)->get();

        // Fetch all vouchers
        $vouchers = Voucher::all();

        return view('home', compact('products', 'vouchers'));
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Fetch sales data
        $totalSales = Order::sum('total_paid');
        $salesOverTime = Order::selectRaw('DATE(created_at) as date, SUM(total_paid) as total')
                             ->groupBy('date')
                             ->orderBy('date', 'asc')
                             ->get();

        // Fetch membership data
        $totalMembers = Membership::count();
        $newMembersOverTime = Membership::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                                  ->groupBy('date')
                                  ->orderBy('date', 'asc')
                                  ->get();

        // Fetch sales by product quantity
        $orders = Order::all();
        $productQuantities = [];
        $typeQuantities = [];

        foreach ($orders as $order) {
            $products = explode(',', $order->product);
            foreach ($products as $product) {
                $product = trim($product);
                if (!isset($productQuantities[$product])) {
                    $productQuantities[$product] = 0;
                }
                $productQuantities[$product] += (int)$order->quantity; // Ensure quantity is cast to integer

                // Split the type column and aggregate quantities
                $types = explode(',', $order->type);
                foreach ($types as $type) {
                    $type = trim($type);
                    if (!isset($typeQuantities[$type])) {
                        $typeQuantities[$type] = 0;
                    }
                    $typeQuantities[$type] += (int)$order->quantity;
                }
            }
        }

        $salesByProduct = collect($productQuantities)->map(function ($quantity, $product) {
            return ['product' => $product, 'quantity' => $quantity];
        })->values();

        $salesByType = collect($typeQuantities)->map(function ($quantity, $type) {
            return ['type' => $type, 'quantity' => $quantity];
        })->values();

        Log::info('Sales by Product:', ['salesByProduct' => $salesByProduct]);
        Log::info('Sales by Type:', ['salesByType' => $salesByType]);

        return view('admin.dashboard', compact('totalSales', 'salesOverTime', 'totalMembers', 'newMembersOverTime', 'salesByProduct', 'salesByType'));
    }
}
