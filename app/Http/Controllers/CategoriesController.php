<?php

namespace App\Http\Controllers;

use App\Http\Service\Catagories\CatagoriesService;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $categoriesService;
    public function __construct(CatagoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }

    public function all_categories() {
        $categories = Category::all();
        return view('admin.categories.all_categories', [
            'categories' => $categories,
        ]);
    }

    public function create_category() {

        return view('admin.categories.create_category');
    }
    public function store_category(Request $request)
    {
        $this->categoriesService->store_category($request);
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục đã được thêm thành công');
    }

    public function edit_category($id) {
        $categories = Category::all();
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.all_categories')->with('message', 'Danh mục không tồn tại');
        }
        return view('admin.categories.edit_category',
                    [
                        'category' => $category,
                        'categories'=> $categories,
                    ]);
    }

    public function update_category(Request $request, $id)
    {
        $this->categoriesService->update_category($request, $id);
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục đã được cập nhật thành công');
    }
    public function remove_category($id) {
        $this->categoriesService->delete_category($id);
        return redirect()->route('admin.all_categories')->with('message', 'Danh mục đã được xóa thành công');
    }
}
