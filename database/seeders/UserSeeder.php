<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                "name" => "admin",
                "email" => "admin@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_ADMIN_ROLE','admin'),
                "is_admin_user" => 1,
            ],
            [
                "name" => "user",
                "email" => "user@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_USER_ROLE','user'),
            ],
            [
                "name" => "Sabbir",
                "email" => "test@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_USER_ROLE','user'),
                "latitude" => "23.8103",
                "longitude" => "90.4125",
                "interests" => json_encode(["Electronics","Books"]),
            ],
            [
                "name" => "Rony",
                "email" => "rony@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_USER_ROLE','user'),
            ],
            [
                "name" => "Mita",
                "email" => "mita@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_USER_ROLE','user'),
            ],
            [
                "name" => "Rashed",
                "email" => "rashed@gmail.com",
                "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
                "role" => env('DEFAULT_USER_ROLE','user'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}