<?php
namespace App\Http\Service\Product;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;

class ProductService{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,CategoryId',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $product = new Product();
        $product->Name = $request->input('name');
        $product->Description = $request->input('description');
        $product->Price = $request->input('price');
        $product->Stock = $request->input('stock');
        $product->CategoryId = $request->input('category_id');
        $product->save();
        if ($request->hasFile('images')) {
            $imageIndex = 1;
            $datePrefix = now()->format('Ymd');
            foreach ($request->file('images') as $image) {
                $filename = $datePrefix . '_' . $product->ProductId . '_' . $imageIndex . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('product_images', $filename, 'public');
                $productImage = new ProductImage();
                $productImage->ProductId = $product->ProductId;
                $productImage->ImageUrl = $path;
                $productImage->save();
                $imageIndex++;
            }
        }
        return true;
    }
    public function update(Request $request, $id) {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.all_products')->with('message', 'Sản phẩm không tồn tại');
        }
        $product->Name = $request->input('name');
        $product->Description = $request->input('description');
        $product->Price = $request->input('price');
        $product->Stock = $request->input('stock');
        $product->CategoryId = $request->input('category_id');
        $product->save();
        if ($request->has('existing_images')) {
            $existingImages = $request->input('existing_images');
            ProductImage::where('ProductId', $product->ProductId)
                ->whereNotIn('ImageUrl', array_map(function($url) {
                    return str_replace(asset('storage/'), '', $url);
                }, $existingImages))
                ->delete();
        } else {
            ProductImage::where('ProductId', $product->ProductId)->delete();
        }
        if ($request->hasFile('images')) {
            $imageIndex = ProductImage::where('ProductId', $product->ProductId)->count() + 1;
            $datePrefix = now()->format('Ymd');
            foreach ($request->file('images') as $image) {
                $filename = $datePrefix . '_' . $product->ProductId . '_' . $imageIndex . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('product_images', $filename, 'public');
                $productImage = new ProductImage();
                $productImage->ProductId = $product->ProductId;
                $productImage->ImageUrl = $path;
                $productImage->save();
                $imageIndex++;
            }
        }
        return true;
    }
    public function destroy($id) {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.all_products')->with('message', 'Sản phẩm không tồn tại');
        }
        foreach ($product->images as $image) {
            $imageUrl = $image->ImageUrl;
            if (!empty($imageUrl)) {
            }
            $image->delete();
        }
        if($product->delete()){
            return true;
        }
        return redirect()->route('admin.all_products')->with('error', 'Sản phẩm đã được xóa Thất bại');
    }
}