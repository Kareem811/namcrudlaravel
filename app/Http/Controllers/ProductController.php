<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // return response()->json(Product::all());
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('category', 'like', "%$search%");
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:1',
            'category' => 'required|string',
            'description' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'images' => json_encode($imagePaths),
        ]);

        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, Product $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'category' => 'sometimes|string',
            'description' => 'sometimes|string',
            'images' => 'sometimes|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle Image Upload
        if ($request->hasFile('images')) {
            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public'); // Store in 'storage/app/public/products'
                $imagePaths[] = $path;
            }

            $validated['images'] = json_encode($imagePaths); // Convert array to JSON for storage
        }

        $id->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $id
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
