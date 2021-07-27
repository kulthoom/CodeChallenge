<?php


namespace App\Traits;


use App\Jobs\UsersEmailProcess;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductDBNotification;
use App\Notifications\ProductNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

trait ProductTrait
{
    public function sendEmailThirdParty(){
        $msg='Please Update the products';
       /* Notification::route('mail' , 'omkulthoomalwaqdi@gmail.com')
            ->notify(new ProductNotification($msg));*/
    }

    public function sendEmailToUsers(){
        $users=User::all();
        $when = Carbon::now()->addSeconds(2);
        //UsersEmailProcess::dispatch($users)->delay($when);
       
    }

    public function notifyDatabase(){
        $when = Carbon::now()->addSeconds(2);
        $products=Product::find(1);
        $counts=Product::count();
        $products->notify((new ProductDBNotification($counts))->delay($when));
    }
}