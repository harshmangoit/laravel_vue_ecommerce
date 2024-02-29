<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            // 'confirmpassword' => 'required|same:password'
        ], [
            // 'confirmpassword.required' => 'The confirm password is required.',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            $token = $user->createToken('laravel_api_token')->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!',
                'accessToken' => $token,
                'userData' => $user,
            ], 200);
        } else {
            return response()->json(['error' => 'Provide proper details'], 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'userData' => $user,
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function address(Request $request)
    {
        UserAddress::create($request->all());
        return response()->json('Address saved successfully', 200);
    }

    public function getAddress(string $id)
    {
        $address = UserAddress::where('user_id', $id)->get();
        return response()->json($address, 200);
    }
    public function updateAddress(Request $request, string $id)
    {
        $address = UserAddress::find($id);

        $address->update($request->all());
        return response()->json('Address updated successfully', 200);
    }

    public function getOrders(string $id)
    {   
        $orders = Order::with(['user', 'address', 'orderItems.product', 'orderItems.order', 'invoice'])->where('user_id', $id)->get();
        return response()->json($orders);
    }
}
