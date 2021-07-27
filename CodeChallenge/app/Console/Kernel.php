<?php

namespace App\Console;

use App\Console\Commands\ImportProducts;
use App\Console\Commands\ImportProductsViaAPI;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
       ImportProducts::class, ImportProductsViaAPI::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //php artisan schedule:run
         $schedule->command('import:proApi')
             ->everyMinute()//hourly(24)
             ->appendOutputTo (public_path().'/Logs/product_api_output.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
