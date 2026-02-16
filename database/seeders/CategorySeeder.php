<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'title' => 'Books', 'status' => '1'],
            ['id' => 2, 'title' => 'Electronics', 'status' => '1'],
            ['id' => 3, 'title' => 'Clothing', 'status' => '1'],
        ];

        foreach ($data as $record) {
            // auto-generate slug
            $record['slug'] = Str::slug($record['title']);

            // check if already exists
            if (!Category::where('id', $record['id'])->exists()) {
                Category::create($record);
            }
        }

    }
}
