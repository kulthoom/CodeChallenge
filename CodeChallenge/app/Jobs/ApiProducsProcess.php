<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApiProducsProcess implements ShouldQueue
{
    private $product;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product=$product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productData=[
            'id' => $this->product['id'],
            'name' => $this->product['name'],
            'price' => $this->product['price'],
            'created_at' => $this->product['created_at'],
            'updated_at' => $this->product['created_at'],

        ];
        Product::updateOrCreate($productData);


    }
}
