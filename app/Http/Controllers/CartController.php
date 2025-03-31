<?php
namespace App\Http\Controllers;

use App\Http\Service\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        $this->cartService->addToCart($request);
        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng'
        ]);
    }
    public function remove(Request $request) {
        $this->cartService->remove($request);
        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng'
        ]);
    }

}
