<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');




// // Daily check for expiring products
        // $schedule->command('products:expire')->daily();

        // // Daily pre-delete notifications
        // $schedule->command('products:notify-deletion')->daily();

        // // Daily delete old products
        // $schedule->command('products:delete-old')->daily();

        // Daily check for garage sale status updates
        // $schedule->command('app:garage-sale-time-rules')->daily();

        // Test run - every minute
        Schedule::command('app:garage-sale-time-rules')->everyMinute();
        Schedule::command('app:product-time-rules')->everyMinute();