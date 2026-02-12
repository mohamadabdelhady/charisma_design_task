<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

it('list all product below threshold', function () {
    Product::factory()->count(10)->create([
        'stock_quantity' => 20,
        'low_stock_threshold' => 5,
    ]);

    Product::factory()->count(5)->create([
        'stock_quantity' => 3,
        'low_stock_threshold' => 5,
    ]);

    expect(Product::count())->toBe(15);

    $response = getJson('/api/products/low-stock');

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

    $response->assertStatus(200);
    expect(count($response->json('data')))->toBe(5);

});
