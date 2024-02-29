<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'address', 'orderItems.product'])->get();
        return response()->json($orders);
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
        $order = Order::create([
            'user_id' => $request->userId,
            // 'address_id' => $request->addressId,
            'total_amount' => $request->totalAmount,
            'status' => $request->status,
            'payment_method' => $request->paymentMethod,
        ]);

        $cartItems = $request->cartItems;

        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'] * $item['quantity'];
            $orderItem->save();
        }

        $orderAddress = new OrderAddress();
        $orderAddress->fill($request->orderAddress);
        $orderAddress->order_id=$order->id;
        $orderAddress->save();

        return response()->json(['message' => 'Order placed successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orders = Order::with(['user', 'address', 'orderItems.product', 'invoice'])->find($id);
        return response()->json($orders);
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
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
