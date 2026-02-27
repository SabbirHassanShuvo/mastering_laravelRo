<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = [
            [
                'title' => 'Privacy Policy',
                'description' => 'This is the privacy policy description.',
                'priority' => 1,
                'status' => Term::STATUS['ACTIVE'],
            ],
            [
                'title' => 'Terms of Service',
                'description' => 'This is the terms of service description.',
                'priority' => 2,
                'status' => Term::STATUS['INACTIVE'],
            ],
            [
                'title' => 'Refund Policy',
                'description' => 'This is the refund policy description.',
                'priority' => 3,
                'status' => Term::STATUS['INACTIVE'],
            ],
        ];

        foreach ($terms as $term) {
            Term::create($term);
        }
    }
}
