<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('update an existing product', function () {
    $product = Product::factory()->create();

    $response = $this->patchJson("/api/products/{$product->id}", [
        'sku' => $product->sku,
        'description' => $product->description,
        'stock_quantity' => $product->stock_quantity,
        'low_stock_threshold' => $product->low_stock_threshold,
        'status' => $product->status,
        'name' => 'Updated Name',
        'price' => 150.00,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Name',
        'price' => 150.00,
    ]);
});
