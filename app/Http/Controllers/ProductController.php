<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Add this line to import the Product model
use App\Models\Category;
use App\Models\Role;
use Auth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:inventory_manager|superadmin');
    }

    public function index(Request $request)
    {
        $sortOrder = $request->query('sort', 'default');
        $categoryId = $request->query('category', 'all');

        $query = Product::with('category');

        // If a category is selected, filter products by category
        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        // Apply sorting
        switch ($sortOrder) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'date_created':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                // No sorting applied
                break;
        }

        $products = $query->get();
        $categories = Category::all(); // Assuming you have a Category model

        return view('products.index', compact('products', 'categories'));
    }

public function create()
{
    $categories = Category::all(); // Fetch all categories
    return view('products.create', compact('categories')); // Pass categories to the view
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required',
        'details' => 'required',
        'price' => 'required|numeric',
        'photo' => 'required|image',
        'category_id' => 'required|exists:category,id',
    ]);

    // Handle the file upload for photo if needed
    if ($request->hasFile('photo')) {
        // Store the photo in the 'photos' folder in the 'public' disk
        $photoPath = $request->file('photo')->store('photos', 'public');
    } else {
        $photoPath = null;
    }

    // Create the new product with photo path and category_id
    $productData = $validatedData;
    $productData['photo'] = $photoPath;

    // Create the product
    $product = Product::create($productData);

    // Check for sizes input and create associated stocks
    if ($request->has('sizes')) {
        foreach ($request->sizes as $size) {
            $product->stocks()->create([
                'size' => $size['size'],
                'quantity' => $size['stock']
            ]);
        }
    }

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

public function show($id)
{
    $product = Product::with(['stocks'])->findOrFail($id);

    // Check if the authenticated user is an admin
    if (auth()->check() && auth()->user()->user_type == 'admin') {
        // Return the admin view if the user is an admin
        return view('products.show', compact('product'));
    }

    // Return the general user view if the user is not an admin
    return view('productdetail', compact('product'));
}


public function edit(Product $product)
{
    $categories = Category::all(); // Fetch all categories to pass to the view

    return view('products.edit', compact('product', 'categories'));
}

public function update(Request $request, Product $product)
{
    // Validate the request data
    $request->validate([
        'name' => 'required',
        'details' => 'required',
        'price' => 'required|numeric',
        'photo' => 'required',
        'category_id' => 'required|exists:category,id', // Make sure this validation rule exists
        // Add more validation rules as needed
    ]);

    // Update the product with the new data
    $product->update($request->all());

    // Redirect to the index page with success message
    return redirect()->route('products.index')
                    ->with('success', 'Product updated successfully.');
}

public function destroy(Product $product)
{
    // Delete the specified product
    $product->delete();

    // Redirect to the index page with success message
    return redirect()->route('products.index')
                    ->with('success', 'Product deleted successfully.');
}

}
