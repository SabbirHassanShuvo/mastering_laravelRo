<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\SpotlightService;
use Illuminate\Console\Command;

class ExpireSpotlights extends Command
{
    // /**
    //  * The name and signature of the console command.
    //  *
    //  * @var string
    //  */
    // protected $signature = 'app:expire-spotlights';
    

    // /**
    //  * The console command description.
    //  *
    //  * @var string
    //  */
    // protected $description = 'Expire spotlighted and normal products based on timeline';

    // /**
    //  * Execute the console command.
    //  */
    // public function handle()
    // {
    //     // Expire normal products (free/paid without spotlight) 48 hours after post
    //     $expiredNormal = Product::where('is_spotlighted', false)
    //         ->where('expires_at', '<=', now())
    //         ->update(['status' => Product::STATUS_EXPIRED]);

    //     // Expire spotlighted products that have ended boost
    //     $spotlightService = app(SpotlightService::class);
    //     $expiredSpotlights = $spotlightService->expireSpotlights();

    //     $this->info("Normal products expired: $expiredNormal");
    //     $this->info("Spotlight expired: $expiredSpotlights");
    // }

    protected $signature = 'spotlight:expire';

    protected $description = 'Expire spotlight products';

    protected $spotlightService;

    public function __construct(SpotlightService $spotlightService)
    {
        parent::__construct();
        $this->spotlightService = $spotlightService;
    }

    public function handle()
    {
        $count = $this->spotlightService->expireSpotlights();

        $this->info("Expired {$count} spotlight products");

        return Command::SUCCESS;
    }
    
}
