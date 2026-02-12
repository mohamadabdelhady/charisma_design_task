<?php

namespace App\Http\Controllers;

use App\Events\StockBelowThreshold;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $cacheKey = "products_page_{$page}_per_{$perPage}";

        $products = Cache::tags('products')->remember($cacheKey, 60, function () use ($perPage) {
            return Product::paginate($perPage);
        });

        return response()->json($products, 200);
    }

    public function show(Product $product)
    {
        return response()->json(['data' => $product], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive', 'discontinued'])],
        ]);

        Product::create($validatedData);

        Cache::tags('products')->flush();

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function update(Product $product, Request $request)
    {
        $validatedData = $request->validate([
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive', 'discontinued'])],
        ]);

        $product->update($validatedData);

        Cache::tags('products')->flush();

        return response()->json(['message' => 'Product updated successfully'], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        Cache::tags('products')->flush();

        return response()->json(['message' => 'Product deleted successfully'], 204);
    }

    public function adjustStock(Product $product, Request $request)
    {
        $validatedData = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'operation' => ['required', Rule::in(['increment', 'decrement'])],
        ]);

        if ($validatedData['operation'] === 'increment') {
            $product->stock_quantity += $validatedData['quantity'];
        } else {
            $product->stock_quantity -= $validatedData['quantity'];
        }

        if ($product->stock_quantity < 0) {
            return response()->json(['message' => 'stock cannot be negative'], 422);
        }

        if ($product->stock_quantity < $product->low_stock_threshold) {
            StockBelowThreshold::dispatch($product);
        }

        $product->save();

        return response()->json(['message' => 'Stock adjusted successfully', 'current stock quantity' => $product->stock_quantity], 200);
    }

    public function listProductBelowThreshhold()
    {
        $products = Product::getProductBelowThreshold();

        return response()->json(['data' => $products], 200);
    }
}
