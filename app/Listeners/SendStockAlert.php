<?php

namespace App\Listeners;

use App\Events\StockBelowThreshold;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendStockAlert
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StockBelowThreshold $event): void
    {
        $product=$event->product;

        Log::warning("The stock for product {$product->name} with sku of ({$product->sku}) is below its threshold and its current quantity is {$product->stock_quantity}");
    }
}
