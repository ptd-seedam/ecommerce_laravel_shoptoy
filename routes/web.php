<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;

// Admin
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/index', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/revenue', [AdminController::class, 'getRevenue'])->name('revenue');
    //users
    Route::prefix('user')->group(function () {
        Route::get('/all_users', [UserController::class, 'all_users'])->name('admin.all_users');
        Route::get('edit/{email}', [UserController::class, 'edit_user'])->name('admin.edit_user');
        Route::post('update/{email}', [UserController::class, 'update_user'])->name('admin.update_user');
        Route::get('remove/{email}', [UserController::class, 'remove_user'])->name('admin.remove_user');
        Route::get('add_user', [UserController::class, 'add_user'])->name('admin.add_user');
        Route::post('save_add_user', [UserController::class, 'save_add_user'])->name('admin.save_add_user');
    });
    // categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoriesController::class, 'all_categories'])->name('admin.all_categories');
        Route::get('/create', [CategoriesController::class, 'create_category'])->name('admin.create_category');
        Route::post('/', [CategoriesController::class, 'store_category'])->name('admin.store_category');
        Route::get('/edit/{id}', [CategoriesController::class, 'edit_category'])->name('admin.edit_category');
        Route::put('/{id}', [CategoriesController::class, 'update_category'])->name('admin.update_category');
        Route::get('/{id}', [CategoriesController::class, 'remove_category'])->name('admin.remove_category');
    });

    // product
    Route::prefix('product')->group(function () {
        Route::get('/all_products', [ProductController::class, 'index'])->name('admin.all_products');
        Route::get('/add_product', [ProductController::class, 'create'])->name('admin.create_product');
        Route::post('/store_product', [ProductController::class, 'store'])->name('admin.store_product');
        Route::get('/edit_product/{id}', [ProductController::class, 'edit'])->name('admin.edit_product');
        Route::post('/update_product/{id}', [ProductController::class, 'update'])->name('admin.update_product');
        Route::delete('/delete_product/{id}', [ProductController::class, 'destroy'])->name('admin.delete_product');
    });
    // discount
    Route::prefix('discount')->group(function () {
        Route::get('/', [DiscountController::class, 'all'])->name('admin.all_discount');
        Route::get('/add', [DiscountController::class, 'add'])->name('admin.add_discount');
        Route::post('/store', [DiscountController::class, 'store'])->name('admin.store_discount');
        Route::get('/edit/{id}', [DiscountController::class, 'edit'])->name('admin.edit_discount');
        Route::post('/edit_exce/{id}', [DiscountController::class, 'updateDiscount'])->name('admin.edit_exce_discount');
        Route::delete('/delete/{id}', [DiscountController::class, 'destroy'])->name('admin.destroy_discount');
        Route::get('/product/{id}', [DiscountController::class, 'product'])->name('admin.discount.product');
    });
    Route::delete('/admin/productdiscounts/{discountId}/{productId}', [DiscountController::class, 'destroy'])
        ->name('admin.productdiscounts.destroy');
    //order
    Route::prefix('order')->group(function () {
        Route::get('/list', [OrderController::class, 'list'])->name('admin.order.list');
        Route::get('/cancel/{id}', [OrderController::class, 'cancel'])->name('admin.order.cancel');
        Route::get('/detail/{id}', [OrderController::class, 'detail'])->name('admin.order.detail');
        Route::post('/ad_edit', [OrderController::class, 'ad_edit']);
        Route::post('/orderitem/edit', [OrderController::class, 'editOrderItem']);
        Route::get('/orderitem/remove/{id}', [OrderController::class, 'removeOrderItem']);
    });
});

// Trang chính
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/product', [WebController::class, 'product'])->name('product');
Route::get('product/{id}',[WebController::class, 'product_detail'])->name('product_detail');
Route::post('/product/{id}/review', [WebController::class, 'storeReview'])->name('product.review');
Route::get('/category/{id}', [WebController::class, 'category_pro'])->name('category_pro');
Route::post('/search', [SearchController::class, 'search'])->name('search');

//cart
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add')->middleware('checklogin');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.removed')->middleware('checklogin');

//checkout
Route::get('/checkout/{id}', [OrderController::class, 'form'])->name('checkout.form')->middleware('checklogin');
Route::post('/order/submit/{cartId}', [OrderController::class, 'submitOrder'])->name('order.submit');
Route::post('/checkout/apply-coupon', [OrderController::class, 'applyCoupon'])->name('order.apply_coupon');
Route::get('/order-success/{order_id}', [OrderController::class, 'success'])->name('order.success');

// Đăng nhập
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/auth-login', [UserController::class, 'auth_login'])->name('auth.login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

//user
Route::post('/user_info/save', [UserController::class, 'save_user_edit'])->name('user.detail.save');
Route::get('/user_info/{id}', [UserController::class, 'info_user'])->name('info_user');