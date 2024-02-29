<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->where('status', 1)->get();
        // Product::all()->with('category')->where('status', 1);
        return $products;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required',
            // image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            'price' => 'required',
            'categoryIds.*' => 'required|exists:categories,id', // Validate that each categoryId exists in the categories table
            'url_slug' => 'required|string|regex:/^[a-zA-Z-]+$/|unique:products',
        ]);

        $product = new Product([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'is_featured' => $request->input('featured'),
            'url_slug' => $request->input('url_slug'),
            'image_path' => $request->input('image')
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Product/Images', 'public');
            $imagePath = "/storage/{$imagePath}";
            $product->image_path = $imagePath;
        }
        $product->save();

        if ($request->has('categoryIds')) {
            foreach ($request->input('categoryIds') as $categoryId) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $categoryId,
                ]);
            }
        }

        return response()->json('Product created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $urlKey)
    {
        $categories = Category::all()->where('status', 1);
        $product = Product::where('url_slug', $urlKey)->with('category')->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }

        $selectedCategoryNames = $product->category()->pluck('name')->toArray();
        // dd($selectedCategoryNames);

        $responseData = [
            'product' => $product,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryNames,
        ];

        return response()->json($responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   //echo $id;
        // $product = Product::find($id);
        // dd($product);
        // return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $urlKey)
    {
        $product = Product::where('url_slug', $urlKey)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // 'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric',
            'category' => 'required|array|exists:categories,id'
        ]);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->url_slug = $request->input('slug');
        $product->is_featured = $request->input('featured');
        $categories = $request->input('category');
        $product->category()->sync($categories);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Product/Images', 'public');
            $imagePath = "/storage/{$imagePath}";
            $product->image_path = $imagePath;
        }
        $product->save();

        return response()->json('Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $urlKey)
    {
        $product = Product::where('url_slug', $urlKey)->first();
        if (!$product) {
            return response()->json('Product not found', 404);
        }
        $product->update(['status' => 0]);
        // $product->delete();
        return response()->json('Product deleted!');
    }
}
