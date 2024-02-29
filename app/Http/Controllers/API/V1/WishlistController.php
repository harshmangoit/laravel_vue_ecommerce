<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // public function index()
    // {
    //     $wishlists = Wishlist::with('product')->get();
    //     return response()->json($wishlists);
    // }
    public function store(Request $request)
    {
        Wishlist::create($request->all());
        return response()->json('Product added to wishlist', 200);
    }
    public function show(string $userId)
    {
        $wishlist = Wishlist::where('user_id', $userId)->with('product')->get();
        return $wishlist;
    }
    public function destroy(string $id)
    {
        $wishlist = Wishlist::find($id);
        if (!$wishlist) {
            return response()->json('Wishlist not found', 404);
        }

        $wishlist->delete();
        return response()->json('Wishlist deleted!');
    }
}
