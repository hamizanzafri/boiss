<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MembershipsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\VoucherController;

// Home routes
Route::get('/', [WelcomeController::class, 'index']);
Route::get('/user', [UserController::class, 'getUser']);
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// User routes
Route::middleware(['role:superadmin'])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

Route::get('/users/admins', [UserController::class, 'adminTable'])->name('users.admintable');
Route::get('/users/general', [UserController::class, 'userTable'])->name('users.usertable');

// General user routes for Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Admin role routes for Products
Route::middleware(['role:inventory_manager|superadmin'])->prefix('admin/products')->group(function () {
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// General user routes for Events
Route::get('/events', [EventController::class, 'index'])->name('event.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('event.show');

// Admin role routes for Events
Route::middleware(['role:event_manager|superadmin'])->prefix('admin/event')->group(function () {
    Route::get('/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/', [EventController::class, 'store'])->name('event.store');
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('event.destroy');
});

// Category routes
Route::middleware(['role:inventory_manager|superadmin'])->prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
});

// Memberships routes
Route::prefix('memberships')->group(function () {
    Route::get('/', [MembershipsController::class, 'index'])->name('memberships.index');
    Route::get('/create', [MembershipsController::class, 'create'])->name('memberships.create');
    Route::post('/', [MembershipsController::class, 'store'])->name('memberships.store');
    Route::get('/{membership}', [MembershipsController::class, 'show'])->name('memberships.show');
    Route::get('/{membership}/edit', [MembershipsController::class, 'edit'])->name('memberships.edit');
    Route::put('/{membership}', [MembershipsController::class, 'update'])->name('memberships.update');
    Route::delete('/{membership}', [MembershipsController::class, 'destroy'])->name('memberships.destroy');
});

// Product detail route (general user view)
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.detail');

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/debug', [CartController::class, 'debugSession']);
});

// Payment routes
Route::prefix('payment')->group(function () {
    Route::post('/', [PaymentController::class,'pay'])->name('payment');
    Route::get('/receipt/{id}', [PaymentController::class, 'showReceipt'])->name('receipt.show');
    Route::get('/ticket/{id}', [PaymentController::class, 'showTicket'])->name('ticket.show');
    Route::get('/both/{id}', [PaymentController::class, 'showBoth'])->name('both.show'); // Updated this route
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/error', [PaymentController::class, 'error'])->name('payment.error');
});

// Environment check route
Route::get('/check-env', [EnvironmentController::class, 'checkEnv']);

// Orders routes
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin dashboard route
Route::get('/admin/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');

// Role-based route groups (leave empty for now)
Route::group(['middleware' => ['role:marketing_manager|superadmin']], function () {
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}', [VoucherController::class, 'show'])->name('vouchers.show');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
});

Route::post('/vouchers/apply', [VoucherController::class, 'apply'])->name('vouchers.apply');
Route::get('/tickets/{ticket}', [EventController::class, 'showTicket'])->name('tickets.show');

Route::get('/test-omnipay', [PaymentController::class, 'testOmnipay']);
