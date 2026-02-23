<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ExpireProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::active()->where('expires_at','<',now())->update(['status'=>Product::STATUS_EXPIRED]);
        $this->info('Expired products updated successfully.');


        // Product::active()
        //     ->where('expires_at', '<=', now()->addMinute(1))
        //     ->update(['status' => Product::STATUS_EXPIRED]);
    }
}
