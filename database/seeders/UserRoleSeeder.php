<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            ['name' => 'Alice', 'email' => 'alice@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'alice.jpg', 'bio' => 'Music is life.'],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'bob.jpg', 'bio' => 'Love rock and blues.'],
            ['name' => 'Charlie', 'email' => 'charlie@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'charlie.jpg', 'bio' => 'Guitarist.'],
            ['name' => 'Diana', 'email' => 'diana@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'diana.jpg', 'bio' => 'Singer-songwriter.'],
            ['name' => 'Eve', 'email' => 'eve@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'eve.jpg', 'bio' => 'Jazz lover.'],
            ['name' => 'Frank', 'email' => 'frank@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'frank.jpg', 'bio' => 'Aspiring DJ.'],
            ['name' => 'Grace', 'email' => 'grace@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'grace.jpg', 'bio' => 'Pianist and composer.'],
            ['name' => 'Hank', 'email' => 'hank@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'hank.jpg', 'bio' => 'Metalhead.'],
            ['name' => 'Ivy', 'email' => 'ivy@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'ivy.jpg', 'bio' => 'Electronic music producer.'],
            ['name' => 'Jack', 'email' => 'jack@example.com', 'password' => Hash::make('password'), 'profile_photo' => 'jack.jpg', 'bio' => 'Classic rock fan.'],
        ]);
    }
}
