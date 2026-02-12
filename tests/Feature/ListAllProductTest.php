<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('List all products with pagination', function () {
    Product::factory()->count(15)->create();

    $response = $this->getJson('api/products');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'sku',
                'name',
                'description',
                'price',
                'stock_quantity',
                'low_stock_threshold',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ],
    ]);

    expect(count($response->json('data')))->toBe(10);

    $responseWithPage = $this->getJson('/api/products?per_page=5&page=2');

    $responseWithPage->assertStatus(200);

    expect(count($responseWithPage->json('data')))->toBe(5);

});
