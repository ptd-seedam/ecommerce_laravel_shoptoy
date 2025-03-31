<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class WebController extends Controller
{
    public function index()
    {
        $recent_Products = Product::where('updated_at', '>=', Carbon::now()->subDays(356))
            ->orderBy('updated_at', 'desc')
            ->with('images')
            ->get();
        $categories = Category::all();
        $cart = Cart::where('UserId', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect([]);
        return view('pages.index', [
            'recent_products' => $recent_Products,
            'categories' => $categories,
            'cartItems' => $cartItems,
        ]);
    }
    public function product(){
        $products = Product::with('images')->get();
        $categories = Category::withCount('products')->get();
        $cart = Cart::where('UserId', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect([]);
        return view('pages.product', [
            'products' => $products,
            'categories' => $categories,
            'cartItems' => $cartItems,
        ]);
    }
    public function category_pro($id){
        $products = Product::where('CategoryId', $id)->with('images')->get();
        $categories = Category::withCount('products')->get();
        $cart = Cart::where('UserId', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect([]);
        return view('pages.product', [
            'products' => $products,
            'categories' => $categories,
            'cartItems' => $cartItems,
        ]);
    }
    public function product_detail($id)
    {
        $products = Product::with('images')->get();
        $product_detail = Product::with(['images', 'reviews.user'])->find($id);
        $average_rating = $product_detail->reviews->avg('Rating') ?? 0;
        $ratings_count = $product_detail->reviews->groupBy('Rating')->map(function ($reviews) {
            return $reviews->count();
        });
        for ($i = 1; $i <= 5; $i++) {
            if (! isset($ratings_count[$i])) {
                $ratings_count[$i] = 0;
            }
        }
        foreach ($product_detail->reviews as $review) {
            $review->created_at = \Carbon\Carbon::parse($review->created_at);
        }
        $cart = Cart::where('UserId', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect([]);
        return view('pages.product_detail', [
            'products' => $products,
            'product_detail' => $product_detail,
            'average_rating' => $average_rating,
            'ratings_count' => $ratings_count,
            'cartItems' => $cartItems,
        ]);
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);
        if (! Auth::check()) {
            return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để gửi đánh giá.');
        }
        $review = new Review;
        $review->ProductId = $id;
        $review->UserId = Auth::id();
        $review->Rating = $request->input('rating');
        $review->Comment = $request->input('comment');
        $review->save();

        return redirect()->back()->with('message', 'Bạn đã đánh giá cho sản phẩm!');
    }
}