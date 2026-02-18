<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Notifications\ProductDeletionNotice;
use Illuminate\Console\Command;

class NotifyProductDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-product-deletion';

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
        // Product::where('created_at','<=',now()->subDays(28))
        //    ->where('status','!=',Product::STATUS_ARCHIVED)
        //    ->get()
        //    ->each(function($product){
        //         $product->user->notify(new ProductDeletionNotice($product));
        //    });
        // $this->info('Pre-delete notifications sent.');


        Product::where('created_at', '<=', now()->subMinute(3))
        ->where('status', '!=', Product::STATUS_ARCHIVED)
        ->get()
        ->each(function ($product) {
            $product->user->notify(new ProductDeletionNotice($product));
        });

        $this->info('Pre-delete notifications sent.');
    }
}
