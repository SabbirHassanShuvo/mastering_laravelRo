<?php

namespace App\Console\Commands;

use App\Models\GarageSale;
use App\Notifications\GarageSaleDeletionNotice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GarageSaleTimeRules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:garage-sale-time-rules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Garage Sale expiry, deletion and notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // =========================
        // 1. Notify 3 days before deletion
        // =========================
        GarageSale::where('status', '!=', 'deleted')
            ->where('notified_before_delete', false)
            ->whereBetween('created_at', [
                $now->copy()->subDays(27),
                $now->copy()->subDays(27)->endOfDay() // ensure full day match
            ])
            ->get()
            ->each(function($sale) {
                $sale->user->notify(new GarageSaleDeletionNotice($sale));
                $sale->notified_before_delete = true;
                $sale->save();
            });

        // =========================
        // 2. Expire sales after 7 days
        // =========================
        GarageSale::where('status', 'active')
            ->where('created_at', '<=', $now->copy()->subDays(7))
            ->update(['status' => 'expired']);

        // =========================
        // 3. Delete sales 30 days after creation
        // =========================
        $salesToDelete = GarageSale::where('created_at', '<=', $now->copy()->subDays(30))->get();
        $deletedCount = 0;

        foreach ($salesToDelete as $sale) {

            // Delete item images from storage
            foreach ($sale->items as $item) {
                foreach ($item->images as $image) {
                    if (Storage::disk('public')->exists($image->photo)) {
                        Storage::disk('public')->delete($image->photo);
                    }
                }
                $item->images()->delete(); // delete image rows
            }

            $sale->items()->delete(); // delete item rows
            $sale->delete();          // delete sale row
            $deletedCount++;
        }

        $this->info("Garage Sales managed successfully. Deleted: {$deletedCount}");


    //     $now = Carbon::now();

    // // =========================
    // // 1. Notify 3 minutes before deletion (test instead of 3 days)
    // // =========================
    // GarageSale::where('status', '!=', 'deleted')
    //     ->where('notified_before_delete', false)
    //     ->whereBetween('created_at', [
    //         $now->copy()->subMinutes(3),
    //         $now->copy()->subMinutes(2)
    //     ])
    //     ->get()
    //     ->each(function($sale) {
    //         $sale->user->notify(new GarageSaleDeletionNotice($sale));
    //         $sale->notified_before_delete = true;
    //         $sale->save();
    //         $this->info("Notification sent for sale ID: {$sale->id} (3 min before deletion)");
    //     });

    // // =========================
    // // 2. Expire sales after 1 minute (test instead of 7 days)
    // // =========================
    // GarageSale::where('status', 'active')
    //     ->where('created_at', '<=', $now->copy()->subMinutes(1))
    //     ->update(['status' => 'expired']);
    // $this->info("Expired sales updated.");

    // // =========================
    // // 3. Delete sales after 5 minutes (test instead of 30 days)
    // // =========================
    // $salesToDelete = GarageSale::where('created_at', '<=', $now->copy()->subMinutes(5))->get();
    // $deletedCount = 0;

    // foreach ($salesToDelete as $sale) {

    //     // Delete item images from storage
    //     foreach ($sale->items as $item) {
    //         foreach ($item->images as $image) {
    //             if (Storage::disk('public')->exists($image->photo)) {
    //                 Storage::disk('public')->delete($image->photo);
    //             }
    //         }
    //         $item->images()->delete();
    //     }

    //     $sale->items()->delete();
    //     $sale->delete();
    //     $deletedCount++;
    //     $this->info("Deleted sale ID: {$sale->id}");
    // }

    // $this->info("Test Garage Sales management complete. Total deleted: {$deletedCount}");
    }
}
