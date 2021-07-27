<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductCsvProcess implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->data as  $row) {
            /*Product::updateOrCreate([
                    'name' => $row[1],
                    'sku' => $row[2],
                    'price' => $row[3],
                    'currency' => $row[4],
                    'variations' => $row[5],
                    'quantity' => $row[6] === NULL ? 0: $row[6],
                    'status' => $row[7],
                ]
            );*/

            Product::create([
                    'name' => $row[1],
                    'sku' => $row[2],
                    'price' => $row[3],
                    'currency' => $row[4],
                    'variations' => json_decode($row[5]),
                    'quantity' => $row[6] === NULL ? 0: $row[6],
                    'status' => $row[7],
                ]
            );

        }
        //delete the file

    }

    public function failed(Throwable $exception)
    {
    }
}
