<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::create([
            'question' => 'How can I create an account?',
            'answer'   => 'Click on the register button and fill up the form.',
            'priority' => 1,
            'status'   => 1,
        ]);

        Faq::create([
            'question' => 'How can I reset my password?',
            'answer'   => 'Go to login page and click forgot password.',
            'priority' => 2,
            'status'   => 1,
        ]);
    }
}
