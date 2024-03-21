<?php

namespace App\Services;

class CsvService
{
    public function parseCsv($filePath)
    {
        $products = [];
        if (($open = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $products[] = [
                    'product' => [
                        'title' => $data[1],
                        'body_html' => $data[2],
                        'vendor' => $data[3],
                        'product_type' => $data[4],
                        'tags' => $data[5]
                    ]
                ];
            }
            fclose($open);
        }
        return $products;
    }
}
