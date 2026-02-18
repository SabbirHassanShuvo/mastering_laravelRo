<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class DeleteOldProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-products';

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
        // Product::where('created_at','<=',now()->subDays(30))->delete();
        // $this->info('Old products deleted.');

        Product::where('created_at', '<', now()->subMinute(1140))->delete();
        $this->info('Old products deleted.');

    }
}
