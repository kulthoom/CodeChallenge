<?php

namespace App\Jobs;

use App\Notifications\ProductNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class UsersEmailProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $users;
    public function __construct($users)
    {
        $this->users =$users ;
        //  Notification::send($users,new ProductNotification());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $msg='out-of-stock products are available';

        Notification::send($this->users,new ProductNotification($msg));
       /* foreach ($this->users as $user){
             Notification::route('mail' , $user->email)
                 ->notify(new ProductNotification());

        }*/
    }
}
