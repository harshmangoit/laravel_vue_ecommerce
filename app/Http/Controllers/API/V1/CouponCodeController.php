<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();
        // Product::all()->with('category')->where('status', 1)->get();
        return response()->json($coupons);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $coupon = Coupon::create($request->all());
        $coupon = new Coupon([
            'code' => $request->input('code'),
            'discount_type' => $request->input('discountType'),
            'discount_amount' => $request->input('discountAmount'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'max_uses' => $request->input('maxUses'),
            // 'current_uses' => $request->input('currentUses')
        ]);

        $coupon->save();

        return response()->json("Coupon created successfully: " . $coupon->code, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code)
    {   
        $coupon = Coupon::where('code', $code)->first();
        return response()->json($coupon);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json('Coupon not found', 404);
        }
        $coupon->update($request->all());
        return response()->json($coupon, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json('Coupon not found', 404);
        }
        // $coupon->update(['status' => 0]);
        $coupon->delete();
        return response()->json('Coupon deleted!');
    }
}
