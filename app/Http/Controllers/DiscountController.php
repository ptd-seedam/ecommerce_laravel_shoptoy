<?php

namespace App\Http\Controllers;

use App\Http\Service\Discount\DiscountService;
use App\Models\Discount;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;


class DiscountController extends Controller
{
    private $discountService;
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function all() {
        $discounts = Discount::all();
        return view('admin.discount.all',[
            'discounts' => $discounts,
        ]);
    }
    public function edit($id){
        $discount = Discount::find($id);
        return view('admin.discount.form',[
            'discount'=>$discount,
        ]);
    }
    public function updateDiscount(Request $request, $id)
    {
        $this->updateDiscount($request, $id);
        return redirect()->route('admin.all_discount')->with('message', 'Cập nhật thành công!');
    }
    public function add(){
        return view('admin.discount.form');
    }
    public function store(Request $request)
    {
        $this->discountService->store($request);
        return redirect()->route('admin.all_discount')->with('message', 'Mã giảm giá đã được thêm thành công.');
    }
    public function destroy($id)
    {
        $this->discountService->deleteDiscount($id);
        return redirect()->route('admin.all_discount')->with('message', 'Mã giảm giá đã được xóa thành công.');
    }
    public function product($id)
    {
        $discount = Discount::find($id);
        $products = $discount ? $discount->getProductsByDiscountId($id) : collect(); // Trả về tập hợp rỗng nếu không tìm thấy discount

        return view('admin.discount.product.all', [
            'products' => $products,
            'discount' => $discount,
        ]);
    }
    public function destroy_products($discountId, $productId)
    {
        $productDiscount = ProductDiscount::where('ProductId', $productId)
        ->where('DiscountId', $discountId)
        ->first();

        if ($productDiscount) {
        $productDiscount->delete();
        return redirect()->back()->with('message', 'Xóa giảm giá thành công.');
        } else {
        return redirect()->back()->with('message', 'Không có sản phẩm.');
        }
    }


}
