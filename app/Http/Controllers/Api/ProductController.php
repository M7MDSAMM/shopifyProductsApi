<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProductsRequest;
use App\Jobs\CreateProductJob;
use App\Services\CsvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $csvService;
    protected $createProductService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
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
}
