<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adjust product stock quamtity', function () {
    $product = Product::factory()->create([
        'stock_quantity' => 20,
    ]);

    $response = $this->postJson("/api/products/{$product->id}/stock", [
        'quantity' => 5,
        'operation' => 'decrement',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock_quantity' => 15,
    ]);
});
