<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Create a new product', function () {
    $response = $this->postJson('/api/products', [
        'sku' => 'sku-300',
        'name' => 'New Product',
        'description' => 'Testing product',
        'price' => 50.00,
        'stock_quantity' => 15,
        'low_stock_threshold' => 5,
        'status' => 'active',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'sku' => 'sku-300',
    ]);
});
