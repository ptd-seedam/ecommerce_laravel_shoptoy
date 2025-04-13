<?php

namespace App\Helper;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class RecommendHelper
{
    public function recommendWithHotSaling()
    {

        $hotSalingProducts = Product::orderBy('so_luong_da_ban', 'desc')
            ->with('images')->take(5)->get();

        return $hotSalingProducts;
    }

    public function recommendWithOrder()
    {
        $order_rcm = Order::where('UserId', Auth::id())->orderBy('created_at', 'desc')->first();
        if (! $order_rcm) {
            $rcm = $this->recommendWithHotSaling();

            return $rcm;
        } else {
            $recommendedProducts = $this->recommendProductsBasedOnOrder($order_rcm);

            return $recommendedProducts;
        }
    }

    protected function recommendProductsBasedOnOrder($order)
    {
        $purchasedProductIds = $order->orderItems->pluck('ProductId')->toArray();
        $purchasedCategories = Product::whereIn('ProductId', $purchasedProductIds)
            ->pluck('CategoryId')
            ->toArray();
        $recommendedProducts = Product::whereIn('CategoryId', $purchasedCategories)
            ->whereNotIn('ProductId', $purchasedProductIds)
            ->with('images')
            ->take(5)
            ->get();

        return $recommendedProducts;
    }
}
