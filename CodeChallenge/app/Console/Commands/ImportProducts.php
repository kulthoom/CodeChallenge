<?php

namespace App\Console\Commands;

use App\Events\NewProductsEvent;
use App\Imports\ProductImport;
use App\Models\Variation;
use App\Repository\ProductCsvRepository;
use App\Repository\ProductCsvRepositoryInterface;
use Illuminate\Console\Command;
use App\Jobs\ProductCsvProcess;
use App\Models\Product;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use PDO;
use Throwable;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //{file}
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports products into database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $productRepository;
    public function __construct(ProductCsvRepository $productRepository)
    {
        parent::__construct();
        $this->productRepository=$productRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

           $data =[]; $header = [];$updatedproducts=[];$product_v=[];$name='';
            $fileFullPath=public_path() .'/CSV/products.csv';
            $contents = file_get_contents($fileFullPath);
            if($this->productRepository->validatePathExtension($fileFullPath)){
               $parts = $this->productRepository->chunckCsv($contents);
//                $batch=Bus::batch([])->dispatch();
                // $batch->add(new ProductCsvProcess($data) );;
                   $productCount=Product::count();
                foreach($parts  as $index=>$line) {
                    $data = $this->productRepository->validateCsvIssues($line);
                    if ($index === 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }

                    foreach($data as  $row) {
                            $productData=[
                                'name' => isset($row[1]) ? $row[1] : null ,
                                'sku' => isset($row[2]) ? $row[2] : '0' ,
                                'price' => isset($row[3]) ? $row[3] : '0' ,
                                'currency' => isset($row[4]) ? $row[4] : 'SAR' ,
                                'quantity' => isset($row[6]) ? $row[6] : '0' ,
                                'status' => isset($row[7]) ? $row[7] : 'sale' ,

                            ];
                            Product::updateOrCreate($productData);
                            Product::where('status','deleted')->delete();

                            $var_json=$row[5];
                           // $var_json=json_decode($row[5],true);
//
                            var_dump($var_json);
                            foreach ($var_json as $k=>$v){
                                try{
                                $name=$v['name'];

//                                Variation::updateOrCreate([
//                                    'name'=>$v['name'],
//                                    'value'=>$v['name']
//                                ]);
                                }catch (\Exception $exception){
                                    dd($k);
                                }

//                                $product_v = Product::find($row[0]);
//                                $product_v->variantion()->attach(
//                                    $v[0],
//                                    ['quantity' => 0,
//                                        'isavailable' => $v['quantity']>0?1:0,
//                                        'addtion_price' => 0
//                                    ]
//                                );

                            }




                        }



                }



            }
            else{
                return 'No such file or directory';
            }


       //php artisan import:products C:\wamp64\www\CodeChallenge\public\CSV\products.csv
      // $fileFullPath=public_path() .'/CSV/products.csv';
//        $fileFullPath = $this->argument('file');
//        Excel::queueImport(new ProductImport(), $fileFullPath);
     
    }

}
