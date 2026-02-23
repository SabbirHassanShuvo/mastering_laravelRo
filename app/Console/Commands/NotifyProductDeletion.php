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
    //    Product::where('status','!=', Product::STATUS_ARCHIVED)
    //     ->where('notified_before_delete', false)
    //     ->whereBetween('created_at', [
    //         $now->copy()->subDays(28),
    //         $now->copy()->subDays(27)
    //     ])
    //     ->get()
    //     ->each(function($product){

    //         $product->user->notify(new ProductDeletionNotice($product));

    //         $product->notified_before_delete = true;
    //         $product->save();
    //     });


        Product::where('status','!=', Product::STATUS_ARCHIVED)
            ->where('notified_before_delete', false)
            ->whereBetween('created_at', [
                $now->copy()->subMinutes(3),
                $now->copy()->subMinutes(2)
            ])
            ->get()
            ->each(function($product){

                $product->user->notify(new ProductDeletionNotice($product));

                $product->notified_before_delete = true;
                $product->save();
            });
        $this->info('Pre-delete notifications sent.');
    }
}
