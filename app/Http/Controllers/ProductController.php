<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Service\Product\ProductService;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }
    public function index() {
        $products = Product::with('category')->get();
        return view('admin.products.all_products', [
            'products' => $products,
        ]);
    }
    public function create() {
        $categories = Category::all();
        return view('admin.products.form', [
            'categories' => $categories,
        ]);
    }
    public function store(Request $request){
        $this->productService->store($request);
        return redirect()->route('admin.all_products')->with('message', 'Sản phẩm đã được thêm thành công');
    }
    public function edit($id) {
        $product = Product::find($id);
        $images = ProductImage::where('ProductId', $id)->get();
        $categories = Category::all();
        if (!$product) {
            return redirect()->route('admin.all_products')->with('message', 'Sản phẩm không tồn tại');
        }
        return view('admin.products.form', [
            'product' => $product,
            'images' => $images,
            'categories' => $categories,
        ]);
    }
    public function update(Request $request, $id) {
        $this->productService->update($request, $id);
        return redirect()->route('admin.all_products')->with('message', 'Sản phẩm đã được cập nhật thành công');
    }

    public function destroy($id) {
        $this->productService->destroy($id);
        return redirect()->route('admin.all_products')->with('message', 'Sản phẩm đã được xóa thành công');
    }

}