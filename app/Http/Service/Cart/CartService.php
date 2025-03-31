<?php
namespace App\Http\Service\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService{
    public function addToCart(Request $request){
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $userId = Auth::id();

        $product = Product::with('images')->find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm.']);
        }
        $cart = Cart::where('UserId', $userId)->first();
        if (!$cart) {
            $cart = Cart::create(['UserId' => $userId]);
        }
        $cartItem = CartItem::where('CartId', $cart->CartId)
                            ->where('ProductId', $productId)
                            ->first();

        if ($cartItem) {
            $cartItem->Quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'CartId' => $cart->CartId,
                'ProductId' => $productId,
                'Quantity' => $quantity
            ]);
        }

        $this->updateCartSession($userId);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
    private function updateCartSession($userId)
    {
        $cart = Cart::where('UserId', $userId)->first();
        $cartItems = $cart ? $cart->cartItems : collect([]);
        Session::put('cart', $cartItems);
    }
    public function remove(Request $request){
        $cartItemId = $request->input('cart_item_id');
        $cartItem = CartItem::find($cartItemId);

        if ($cartItem) {
            $cartItem->delete();
            if(Session(key: 'cart')){
                $request->session()->forget('cart'); // Xóa tất cả session
            }
            return response()->json(['success' => true]);
        }

        return true;
    }
}
