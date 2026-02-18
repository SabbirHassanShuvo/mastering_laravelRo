<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        foreach (range(1, 20) as $i) {

            $postedAt = now()->subDays(rand(0, 10));
            $expiresAt = $postedAt->copy()->addDays(7);

            $status = Product::STATUS_ACTIVE;

            if ($expiresAt->isPast()) {
                $status = Product::STATUS_EXPIRED;
            }

            Product::create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => 'Sample Product ' . $i,
                'product_type' => collect(['paid', 'free', 'garage_sale'])->random(),
                'price' => rand(100, 5000),
                'condition_status' => 'Used',
                'description' => 'This is demo product description.',
                'pickup_location' => 'Dhaka',
                'pickup_latitude' => 23.8103,
                'pickup_longitude' => 90.4125,
                'status' => $status,
                'posted_at' => $postedAt,
                'expires_at' => $expiresAt,
                'product_image' => null,
            ]);
        }
    }
}
