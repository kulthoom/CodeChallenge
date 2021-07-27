<?php

namespace App\Imports;

use App\Events\NewProductsEvent;
use App\Models\Product;
use App\Repository\ProductCsvRepository;
use App\Traits\ProductTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Throwable;

class ProductImport implements WithChunkReading,ToCollection, ShouldQueue,WithHeadingRow,SkipsOnError,
    WithValidation,WithEvents,
    SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use SkipsErrors,Importable,RegistersEventListeners,ProductTrait;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
       $var_json=[];$proArray=[];

        $products=Product::count();
        if($products >0){
            foreach ($rows as $row)
            {
                $productData=[
                    'name' => $row['name'],
                    'sku' => $row['sku'],
                    'price' => $row['price'],
                    'currency' => $row['currency'],
                    'quantity' => $row['quantity'],
                    'status' => $row['status'],
                ];
                $proArray=Product::where('id',$row['id'])->update($productData);
            }
            //Fire Event
            event(new NewProductsEvent());

        }else{
            foreach ($rows as $row)
            {
                $productData=[
                    'name' => $row['name'],
                    'sku' => $row['sku'],
                    'price' => $row['price'],
                    'currency' => $row['currency'],
                    //  'variations' => json_decode($row['variations'],true),
                    'quantity' => $row['quantity'],
                    'status' => $row['status'],
                ];
                $proArray=Product::create($productData);
                //Delete The products with status deleted
                 Product::where('status','deleted')->delete();
                // $var_json=json_decode($row['variations'],true);

//            foreach($var_json as $v){
//              ProductVariationsName::updateOrCreate(
//                [  'name'=>$v['name'],]
//               );
//            }
            }


        }

    }


    public function chunkSize(): int
    {
        return 1000;
    }


    /**
     * @return array
     */
    public function rules(): array
    {
       return[
           'file' => 'required|file|max:4096|mimes:xls,xlsx,csv',
           'sku' => 'required',
           'price' => 'required',
           'currency' => 'required',
           'quantity' => 'required',
           'status' => 'required',
       ];
    }

    /**
     * @param Throwable $e
     */
    public function onError(Throwable $e)
    {
        Log::error($e);
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        Log::error($failures);
    }

}
