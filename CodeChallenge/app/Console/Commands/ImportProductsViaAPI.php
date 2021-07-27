<?php

namespace App\Console\Commands;

use App\Jobs\ApiProducsProcess;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ImportProductsViaAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:proApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import products from third party api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $variate=[];
        $products=Http::withOptions(['verify' => false])->get('https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products')
            ->json();
        foreach($products as $product){

           // ApiProducsProcess::dispatch($product);

            $productData=[
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['created_at'],

            ];
            Product::updateOrCreate($productData);
           $proVariations= $product['variations'];

           foreach ($proVariations as $v){
               $key=array_keys($v);
              $variate= Variation::updateOrCreate([
                   'name'=>implode("-",[$key[2],$key[3]]),
                   'value'=>implode("-",[$v['color'],$v['material']])
               ]);
               $product_v = Product::find($v['productId']);
               $product_v->variantion()->attach(
                   $v['id'],
                   ['quantity' => $v['quantity'],
                     'isavailable' => $v['quantity']>0?1:0,
                     'addtion_price' => $v['additional_price']
                   ]
               );
           }



        }


        $this->line('==================');
        $this->line('Running sync at ' . Carbon::now());
        $this->line('Ending sync at ' . Carbon::now());


    }
}
