<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProductsRequest;
use App\Jobs\CreateProductJob;
use App\Services\CsvService;
use App\Services\GetProductsService;
use App\Services\ProductsCleaningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $csvService;
    protected $getProductsService;
    protected $productsCleaningService;

    public function __construct(CsvService $csvService, GetProductsService $getProductsService, ProductsCleaningService $productsCleaningService)
    {
        $this->csvService = $csvService;
        $this->getProductsService = $getProductsService;
        $this->productsCleaningService = $productsCleaningService;
    }

    public function uploadProducts(UploadProductsRequest $request)
    {
        $path = $request->file('csv_file')->store('temp');
        $path = Storage::path($path);
        $products = $this->csvService->parseCsv($path);
        foreach ($products as $product) {
            dispatch(new CreateProductJob($product));
        }

        return response()->json(['message' => 'Products are being uploaded'], 200);
    }

    public function getProducts()
    {
        $products = $this->getProductsService->fetchProducts();
        $cleanedProducts = $this->productsCleaningService->clean($products);
        return $cleanedProducts;
    }
}
