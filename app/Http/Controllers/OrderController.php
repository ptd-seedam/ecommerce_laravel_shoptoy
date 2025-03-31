<?php

namespace App\Http\Controllers;

use App\Http\Service\Order\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function form($id)
    {
        $this->orderService->form($id);
    }

    public function submitOrder(Request $request, $cartId)
    {
        $this->orderService->submitOrder($request, $cartId);
    }
    public function applyCoupon(Request $request)
    {
        $this->orderService->applyCoupon($request);
    }
    public function success($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        return view('pages.checkout_success', compact('order'));
    }
    public function list(){
        $order = Order::all();
        return view('admin.order.list',[
            'orders' => $order,
        ]);
    }
    public function cancel($id){
        $order= Order::find($id);
        $order->OrderStatus = 'Cancelled';
        $order->save();
        return redirect()->back()->with('message', 'Đã hủy Đơn');
    }
    public function detail($id){
        $order = Order::find($id);
        $OrderItems = OrderItem::where('OrderId' ,$id)->get();
        return view('admin.order.detail',
            [
                'order'=> $order,
                'orderItems' => $OrderItems,
            ]);
    }
    public function ad_edit(Request $request) {
        $this->orderService->ad_edit($request);
        return redirect()->back()->with('message', 'Cập nhật thành công');
    }
    public function editOrderItem(Request $request) {
        $this->orderService->editOrderItem($request);
        return redirect()->back()->with('message', 'Cập nhật hoàn tất');
    }
    public function removeOrderItem($orderItemId) {
        $orderItem = OrderItem::find($orderItemId);
        if ($orderItem) {
            $orderItem->delete();
        }
        return redirect()->back()->with('message', 'Đã xóa sản phẩm');
    }





}
