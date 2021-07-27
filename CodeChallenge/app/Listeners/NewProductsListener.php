<?php

namespace App\Listeners;

use App\Events\NewProductsEvent;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductDBNotification;
use App\Repository\ProductCsvRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewProductsListener implements ShouldQueue
{
    use Queueable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $rep;
    public function __construct(ProductCsvRepository $rep)
    {
        $this->rep=$rep;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewProductsEvent $event)
    {

       //Email for 3rd Party to update
        $this->rep->sendEmailThirdParty();
        //Email For Users
        $this->rep->sendEmailToUsers();
        //Notify database
        $this->rep->notifyDatabase();

    }
}
