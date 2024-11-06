<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        User::create([
            'user' => 'raulntjj',
            'email' => 'raulntjj@gmail.com',
            'name' => 'Raul',
            'last_name' => 'De Oliveira',
            'password' => '12345678',
            'profile_photo' => 'x',
            'bio' => 'Hello World!',
            'is_private' => false
        ]);
    }
}
