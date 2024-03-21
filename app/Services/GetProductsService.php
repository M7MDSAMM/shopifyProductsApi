<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GetProductsService
{
    public function fetchProducts()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => env('SHOPIFY_ACCESS_TOKEN'),
        ])->get(env('SHOPIFY_SHOP_URL'));
        return $response->json();
    }
}
