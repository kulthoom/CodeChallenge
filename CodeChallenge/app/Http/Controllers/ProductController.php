<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Jobs\ProductCsvProcess;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function importProducts(Request $request){

        Excel::queueImport(new ProductImport(), $request->file);
        return true;
    }


    public function getDataAPI(){
        $products=Http::get('https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products')
            ->json();
        foreach($products as $product){
            $productData=[
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['created_at'],

            ];
            Product::updateOrCreate($productData);
        }

    }
}


