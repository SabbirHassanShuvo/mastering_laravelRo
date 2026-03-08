<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Notifications\ProductDeletionNotice;
use App\Notifications\ProductExpiryNotice;
use Illuminate\Console\Command;

class ProductTimeRules extends Command
{
    // /**
    //  * The name and signature of the console command.
    //  *
    //  * @var string
    //  */
    // protected $signature = 'app:product-time-rules';

    // /**
    //  * The console command description.
    //  *
    //  * @var string
    //  */
    // protected $description = 'Command description';

    // /**
    //  * Execute the console command.
    //  */
    // public function handle()
    // {
    //     $now = now();

    //     // 0. 24h before expiry notification
    //     $productsToNotifyExpiry = Product::where('status', Product::STATUS_ACTIVE)
    //         ->where('notified_before_expiry', false)
    //         ->whereNotNull('expires_at')
    //         ->whereBetween('expires_at', [$now, $now->copy()->addDay(1)])
    //         ->get();

    //     foreach ($productsToNotifyExpiry as $product) {
    //         $product->user->notify(new ProductExpiryNotice($product));
    //         $product->notified_before_expiry = true;
    //         $product->save();
    //         $this->info("Notified user for product ID: {$product->id} (24h before expiry)");
    //     }

    //     // // 1. Expire products
    //     $expiredCount = Product::active()
    //         ->where('expires_at','<',$now)
    //         ->update(['status'=>Product::STATUS_EXPIRED]);
    //     $this->info("$expiredCount products expired.");

    //     // 2. Notify 3 days before delete
    //     $productsToNotifyDelete = Product::where('status','!=', Product::STATUS_ARCHIVED)
    //         ->where('notified_before_delete', false)
    //         ->whereBetween('created_at', [
    //             $now->copy()->subDays(27),
    //             $now->copy()->subDays(26)
    //         ])
    //         ->get();

    //     foreach($productsToNotifyDelete as $product){
    //         $product->user->notify(new ProductDeletionNotice($product));
    //         $product->notified_before_delete = true;
    //         $product->save();
    //         $this->info("Notified user for product ID: {$product->id} (3 days before delete)");
    //     }

    //     // 3. Delete after 30 days
    //     $deletedCount = Product::where('created_at','<=',$now->copy()->subDays(30))->delete();
    //     $this->info("$deletedCount old products deleted.");


    //     // Testing with minutes instead of days for quicker verification

    //     // $now = now();

    //     // // 0. 1 minute before expiry notification
    //     // $productsToNotifyExpiry = Product::where('status', Product::STATUS_ACTIVE)
    //     //     ->where('notified_before_expiry', false)
    //     //     ->whereNotNull('expires_at')
    //     //     ->whereBetween('expires_at', [$now, $now->copy()->addMinute(1)])
    //     //     ->get();

    //     // foreach ($productsToNotifyExpiry as $product) {
    //     //     $product->user->notify(new ProductExpiryNotice($product));
    //     //     $product->notified_before_expiry = true;
    //     //     $product->save();
    //     //     $this->info("Notified user for product ID: {$product->id} (1 min before expiry)");
    //     // }

    //     // // 1. Expire products
    //     // $expiredCount = Product::active()
    //     //     ->where('expires_at','<',$now)
    //     //     ->update(['status'=>Product::STATUS_EXPIRED]);
    //     // $this->info("$expiredCount products expired.");

    //     // // // 2. Notify 1 min before delete
    //     // $productsToNotifyDelete = Product::where('status','!=', Product::STATUS_ARCHIVED)
    //     //     ->where('notified_before_delete', false)
    //     //     ->whereBetween('created_at', [
    //     //         $now->copy()->subMinutes(6),
    //     //         $now->copy()->subMinutes(5)
    //     //     ])
    //     //     ->get();

    //     // foreach($productsToNotifyDelete as $product){
    //     //     $product->user->notify(new ProductDeletionNotice($product));
    //     //     $product->notified_before_delete = true;
    //     //     $product->save();
    //     //     $this->info("Notified user for product ID: {$product->id} (1 min before delete)");
    //     // }

    //     // // // 3. Delete after 30 minutes
    //     // $deletedCount = Product::where('created_at','<=',$now->copy()->subMinutes(8))->delete();
    //     // $this->info("$deletedCount old products deleted.");
    // }


    protected $signature = 'products:time-rules';

    protected $description = 'Handle product expiry, notifications and deletion';

    public function handle()
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | 1. Notify 24h before expiry
        |--------------------------------------------------------------------------
        */

        $productsToNotifyExpiry = Product::where('status', Product::STATUS_ACTIVE)
            ->where('notified_before_expiry', false)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [$now, $now->copy()->addDay()])
            ->get();

        foreach ($productsToNotifyExpiry as $product) {

            $product->user->notify(new ProductExpiryNotice($product));

            $product->update([
                'notified_before_expiry' => true
            ]);

            $this->info("Expiry notification sent for product {$product->id}");
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Expire products
        |--------------------------------------------------------------------------
        */

        $expiredCount = Product::where('status', Product::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->update([
                'status' => Product::STATUS_EXPIRED
            ]);

        $this->info("$expiredCount products expired");

        /*
        |--------------------------------------------------------------------------
        | 3. Notify 3 days before deletion
        |--------------------------------------------------------------------------
        */

        $productsToNotifyDelete = Product::where('status','!=', Product::STATUS_ARCHIVED)
            ->where('notified_before_delete', false)
            ->whereBetween('created_at', [
                $now->copy()->subDays(27),
                $now->copy()->subDays(26)
            ])
            ->get();

        foreach ($productsToNotifyDelete as $product) {

            $product->user->notify(new ProductDeletionNotice($product));

            $product->update([
                'notified_before_delete' => true
            ]);

            $this->info("Deletion warning sent for product {$product->id}");
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Delete products after 30 days
        |--------------------------------------------------------------------------
        */

        $deletedCount = Product::where('created_at','<=',$now->copy()->subDays(30))->delete();

        $this->info("$deletedCount products deleted");

        return Command::SUCCESS;
    }
    
}
