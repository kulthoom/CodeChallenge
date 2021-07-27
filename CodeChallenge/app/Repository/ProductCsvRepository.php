<?php


namespace App\Repository;


use App\Jobs\UsersEmailProcess;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductDBNotification;
use App\Notifications\ProductNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ProductCsvRepository implements ProductCsvRepositoryInterface
{
    public function validatePathExtension($file){
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        $formats = ['xls', 'xlsx', 'ods', 'csv'];
        if(in_array($fileExtension, $formats) && $file !=null){
            return true;
        }else{return false;}
    }

    public function validateCsvIssues($csv){
        $csv=preg_replace('~\r\n?~', "\n", $csv);
        $csv = $csv === '' ? null : $csv;
        $csv=preg_replace('/NULL/', '0', $csv);
        //$csv=str_replace("\r","\n",$csv);
        $data = array_map('str_getcsv',$csv);
        return array_map('str_getcsv',$csv);

    }

    public function chunckCsv($contents){
        $data = explode("\n", $contents);
        // $data  = array_slice($lines, 1);
        $parts = (array_chunk($data, 1000));
        return $parts;
    }

    public function sendEmailThirdParty(){
        $msg='Please Update the products';
        Notification::route('mail' , 'omkulthoomalwaqdi@gmail.com')
            ->notify(new ProductNotification($msg));
    }

    public function sendEmailToUsers(){
        $users=User::all();
        $when = Carbon::now()->addSeconds(2);
        UsersEmailProcess::dispatch($users)->delay($when);

    }

    public function notifyDatabase(){
        $when = Carbon::now()->addSeconds(2);
        $products=Product::find(1);
        $counts=Product::count();
        $products->notify((new ProductDBNotification($counts))->delay($when));
    }
}