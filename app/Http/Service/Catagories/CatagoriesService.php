<?php
namespace App\Http\Service\Catagories;

use App\Models\Category;
use Illuminate\Http\Request;

class CatagoriesService
{
    public function store_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_category_id' => 'nullable|integer|exists:categories,CategoryId',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $parentCategoryId = $request->input('parent_category_id');
        if ($parentCategoryId !== null) {
            $category->parent_category_id = $parentCategoryId;
        }
        if($category->save()){
            return true;
        }
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục thêm không thành công');
    }
    public function update_category(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_category_id' => 'nullable|integer|exists:categories,CategoryId',
        ]);
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.all_categories')->with('message', 'Danh mục không tồn tại');
        }
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $parentCategoryId = $request->input('parent_category_id');
        if ($parentCategoryId !== null) {
            $category->parent_category_id = $parentCategoryId;
        }
        if($category->save()){
            return true;
        }
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục cập nhật không thành công');
    }
    public function delete_category($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.all_categories')->with('message', 'Danh mục không tồn tại');
        }
        if($category->delete()){
            return true;
        }
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục xóa không thành công');
    }
}
