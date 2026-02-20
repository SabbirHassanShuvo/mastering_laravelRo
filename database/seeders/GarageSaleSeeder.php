<?php

namespace Database\Seeders;

use App\Models\GarageItem;
use App\Models\GarageItemImage;
use App\Models\GarageSale;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GarageSaleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $userId = 1; 

        for ($i = 0; $i < 5; $i++) {
            $saleStart = $faker->dateTimeBetween('-1 month', '+1 month');
            $saleEnd = (clone $saleStart)->modify('+'.rand(1,5).' days');

            $garage = GarageSale::create([
                'user_id' => $userId,
                'event_title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'date' => $faker->date(),
                'pickup_location' => $faker->city,
                'sale_start_date' => $saleStart,
                'sale_end_date' => $saleEnd,
                'expires_at' => Carbon::parse($saleEnd)->addDays(7),
                'posting_fee' => 2.99,
                'total_fee' => $faker->randomFloat(2, 10, 100),
                'status' => 'active'
            ]);

            $itemsCount = rand(3, 6);
            for ($j = 0; $j < $itemsCount; $j++) {
                $item = GarageItem::create([
                    'garage_sale_id' => $garage->id,
                    'title' => $faker->word,
                    'price' => $faker->randomFloat(2, 5, 100),
                    'description' => $faker->sentence
                ]);

                $imagesCount = rand(1, 3);
                for ($k = 0; $k < $imagesCount; $k++) {
                    GarageItemImage::create([
                        'garage_item_id' => $item->id,
                        'photo' => 'fake_images/' . $faker->image('storage/app/public/fake_images', 640, 480, null, false)
                    ]);
                }
            }
        }
    }
}