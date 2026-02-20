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

        // 2 days before deletion -> notify
        GarageSale::where('status','!=','deleted')
            ->where('expires_at','<=',$now->copy()->addDays(2))
            ->get()
            ->each(function($sale){
                $sale->user->notify(new GarageSaleDeletionNotice($sale));
            });

        // Expire sale after 7 days
        // GarageSale::where('status','active')
        //     ->where('expires_at','<=',$now)
        //     ->update(['status'=>'expired']);

        // Delete sales 30 days after creation
        // $salesToDelete = GarageSale::where('created_at', '<=', $now->copy()->subDays(30))
        //     ->get();

        // $deletedCount = 0;

        // foreach ($salesToDelete as $sale) {

        //     // Delete item images from storage
        //     foreach ($sale->items as $item) {
        //         foreach ($item->images as $image) {
        //             if (Storage::disk('public')->exists($image->photo)) {
        //                 Storage::disk('public')->delete($image->photo);
        //             }
        //         }
        //         // Delete image rows
        //         $item->images()->delete();
        //     }

        //     // Delete item rows
        //     $sale->items()->delete();

        //     // Delete sale row
        //     $sale->delete(); // <-- full row delete
        //     $deletedCount++;
        // }

        // Delete sales 1 minute after creation (test purpose)
        $salesToDelete = GarageSale::where('created_at', '<=', $now->copy()->subMinutes(1))
            ->get();

        $deletedCount = 0;

        foreach ($salesToDelete as $sale) {

            // Delete item images from storage
            foreach ($sale->items as $item) {
                foreach ($item->images as $image) {
                    if (Storage::disk('public')->exists($image->photo)) {
                        Storage::disk('public')->delete($image->photo);
                    }
                }
                // Delete image rows
                $item->images()->delete();
            }

            // Delete item rows
            $sale->items()->delete();

            // Delete sale row
            $sale->delete(); // full row delete
            $deletedCount++;
        }

        $this->info("Garage Sales managed successfully. Deleted: {$deletedCount}");
    }
}
