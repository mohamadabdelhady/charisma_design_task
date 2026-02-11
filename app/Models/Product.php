<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'sku',
    'name',
    'description',
    'price',
    'stock_quantity',
    'low_stock_threshold',
    'status',
    ];

    public function getProductBelowThreshold()
    {
       return $this->where('stock_quantity','<','low_stock_threshold');
    }
}
