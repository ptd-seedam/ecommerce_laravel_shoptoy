<?php

namespace App\Http\Service\Order;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderService
{
    public function form($id)
    {
        $cart = Cart::find($id);
        $cartItems = $cart->cartItems()->with('product')->get();
        $userId = Auth::id();
        $address = DB::table('users')->where('id', $userId)->value('Address');
        $total = $cartItems->sum(function ($item) {
            return $item->product->Price * $item->Quantity;
        });
        $discount = Session::get('discount');
        if ($discount) {
            if ($discount->DiscountType === 'percentage') {
                $total -= $total * ($discount->DiscountValue / 100);
            } elseif ($discount->DiscountType === 'fixed') {
                $total -= $discount->DiscountValue;
            }
            $total = max($total, 0);
        }
        Session::put('cart_ID', $cart->CartId);

        return view('pages.checkout_form', [
            'address' => $address,
            'cartItems' => $cartItems,
            'total' => $total,
            'cartId' => $id,
        ]);
    }

    public function submitOrder(Request $request, $cartId)
    {
        $cart = Cart::with('cartItems.product')->findOrFail($cartId);
        $totalAmount = $cart->cartItems->sum(function ($item) {
            return $item->product->Price * $item->Quantity;
        });
        $discount = Session::get('discount');
        if ($discount) {
            if ($discount->DiscountType === 'Percentage') {
                $totalAmount -= $totalAmount * ($discount->DiscountValue / 100);
            } elseif ($discount->DiscountType === 'Fixed Amount') {
                $totalAmount -= $discount->DiscountValue;
            }
            $totalAmount = max($totalAmount, 0);
        }
        $order = Order::create([
            'UserId' => Auth::id(),
            'OrderStatus' => 'Pending',
            'TotalAmount' => $totalAmount,
            'PaymentStatus' => 'Pending',
            'ShippingAddress' => $request->input('address'),
        ]);
        foreach ($cart->cartItems as $item) {
            OrderItem::create([
                'OrderId' => $order->OrderId,
                'ProductId' => $item->ProductId,
                'Quantity' => $item->Quantity,
                'UnitPrice' => $item->product->Price,
            ]);
        }
        $cart->cartItems()->delete();
        $cart->delete();
        Session::forget('discount');
        Shipment::create([
            'OrderId' => $order->OrderId,
            'TrackingNumber' => 'N/A',
            'ShippedDate' => null,
            'EstimatedDeliveryDate' => null,
            'DeliveryStatus' => 'Pending',
        ]);
        Payment::create([
            'OrderId' => $order->OrderId,
            'PaymentMethod' => 'Cash on Delivery',
            'PaymentDate' => null,
            'Amount' => $totalAmount,
        ]);
        Session::forget('cart');

        return redirect()->route('order.success', ['order_id' => $order->OrderId]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
        $couponCode = $request->input('coupon_code');
        $discount = Discount::where('DiscountCode', $couponCode)
            ->where('IsActive', true)
            ->whereDate('StartDate', '<=', now())
            ->whereDate('EndDate', '>=', now())
            ->first();

        if (! $discount) {
            return response()->json([
                'success' => false,
                'error' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
            ]);
        }
        $userId = Auth::id();
        $cartId = Session::get('cart_ID');
        $cart = Cart::where('UserId', $userId)->where('id', $cartId)->first();
        if (! $cart) {
            return response()->json([
                'success' => false,
                'error' => 'Không tìm thấy giỏ hàng.',
            ]);
        }
        $cartItems = $cart->cartItems()->with('product')->get();
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->Price * $item->Quantity;
        }
        if ($discount->DiscountType === 'Percentage') {
            $total -= $total * ($discount->DiscountValue / 100);
        } elseif ($discount->DiscountType === 'Fixed Amount') {
            $total -= $discount->DiscountValue;
        }
        $total = max($total, 0);
        Session::put('discount', $discount);
        Session::put('cart_total', $total);

        return response()->json([
            'success' => true,
            'new_total' => number_format($total, 0, ',', '.').' VNĐ',
        ]);
    }

    // apply coupon
    public function ad_edit(Request $request)
    {
        $orderId = $request->input('OrderId');
        $orderStatus = $request->input('OrderStatus');
        $paymentStatus = $request->input('PaymentStatus');
        $order = Order::find($orderId);
        if ($order) {
            $order->OrderStatus = $orderStatus;
            $order->PaymentStatus = $paymentStatus;
            $order->save();
        }

        return true;
    }

    public function editOrderItem(Request $request)
    {
        $orderItemId = $request->input('OrderItemId');
        $quantity = $request->input('Quantity');
        $orderItem = OrderItem::find($orderItemId);
        if ($orderItem) {
            $orderItem->Quantity = $quantity;
            $orderItem->save();
            $order = $orderItem->order;
            $orderTotal = $order->orderItems->sum('TotalPrice');
            $order->TotalAmount = $orderTotal;
            $order->save();
        }

        return true;
    }
}
