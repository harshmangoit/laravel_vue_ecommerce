<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
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
        $category = Category::create($request->all());
        return response()->json($category, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_key)
    {
        $category = Category::with('products')->where('slug', $url_key)->first();

        return response()->json($category);
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
    public function update(Request $request, string $urlKey)
    {
        $category = Category::where('slug', $urlKey)->first();
        if (!$category) {
            return response()->json('Category not found', 404);
        }
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json('Category not found', 404);
        }
        $category->delete();
        return response()->json('Category deleted!');
    }
}
