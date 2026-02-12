<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('get the data of a single product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson("/api/products/{$product->id}");

    $response->assertStatus(200)->assertJsonFragment([
        'id' => $product->id,
        'sku' => $product->sku,
        'name' => $product->name,
        'price' => $product->price,
        'stock_quantity' => $product->stock_quantity,
        'low_stock_threshold' => $product->low_stock_threshold,
        'status' => $product->status,
    ]);
});
