<?php

namespace App\Http\Service\Discount;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountService
{
    public function updateDiscount(Request $request, $id)
    {
        DB::table('discounts')
            ->where('id', $id)
            ->update([
                'DiscountCode' => $request->input('DiscountCode'),
                'Description' => $request->input('Description'),
                'DiscountType' => $request->input('DiscountType'),
                'DiscountValue' => $request->input('DiscountValue'),
                'StartDate' => $request->input('StartDate'),
                'EndDate' => $request->input('EndDate'),
                'IsActive' => $request->input('IsActive', 1),
            ]);

        return true;
    }

    public function store(Request $request)
    {
        $request->validate([
            'DiscountCode' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'DiscountType' => 'required|string',
            'DiscountValue' => 'required|numeric',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date',
        ]);

        DB::table('discounts')->insert([
            'DiscountCode' => $request->input('DiscountCode'),
            'Description' => $request->input('Description'),
            'DiscountType' => $request->input('DiscountType'),
            'DiscountValue' => $request->input('DiscountValue'),
            'StartDate' => $request->input('StartDate'),
            'EndDate' => $request->input('EndDate'),
        ]);

        return true;
    }

    public function deleteDiscount($id)
    {
        DB::table('discounts')->where('DiscountId', $id)->delete();

        return true;
    }
}
