<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pname' => 'required|string',
            'pprice' => 'required|numeric',
            'pcategory' => 'required|string',
            'pdescription' => 'required|string',
            'pimgs' => 'required|array',
            'pimgs.*' => 'file|mimes:jpg,jpeg,png|max:2048', // Validate each image
        ]);

        $imagePaths = [];
        if ($request->hasFile('pimgs')) {
            foreach ($request->file('pimgs') as $file) {
                $path = $file->store('products', 'public'); // Save in the 'storage/app/public/products' directory
                $imagePaths[] = $path;
            }
        }

        $product = Product::create([
            'pname' => $validated['pname'],
            'pprice' => $validated['pprice'],
            'pcategory' => $validated['pcategory'],
            'pdescription' => $validated['pdescription'],
            'pimgs' => json_encode($imagePaths),
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
            'pname' => 'string',
            'pprice' => 'numeric',
            'pcategory' => 'string',
            'pdescription' => 'string',
            'pimgs' => 'array',
        ]);

        $id->update($validated);
        return response()->json(['message' => 'Product updated successfully', 'product' => $id]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
