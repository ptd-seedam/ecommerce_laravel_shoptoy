<?php
namespace App\Http\Service;

use App\Models\Cart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class AdminService
{
    public function getRevenue(Request $request){
        $month = $request->input('month');
        $year = $request->input('year');

        if (is_null($month) || is_null($year)) {
            return redirect()->back()->with('error', 'Vui lòng chọn cả tháng và năm!');
        }
        $newUser = User::where('updated_at', '>=', Carbon::now()->subDays(90))
            ->orderBy('updated_at', 'desc')->count();
        $totalUser = User::all()->count();
        $totalCart = Cart::all()->count();
        $totalOrder = Order::all()->count();
        $totalRevenue = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('TotalAmount');

        return view('admin.index', [
            'totalRevenue' => $totalRevenue,
            'month' => $month,
            'yeards' => $year,
            'newUser' => $newUser,
            'totalUser' => $totalUser,
            'totalCart' => $totalCart,
            'totalOrder' => $totalOrder,
        ]);
    }
}
