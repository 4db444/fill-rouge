<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            "first_name" => "brahim",
            "last_name" => "alhiane",
            "email" => "brahim@gmail.com",
            "password" => Hash::make("brahimbrahim"),
            "role" => "admin"
        ]);

        $this->call([
            UserSeeder::class
        ]);
    }
}
