<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        User::query()->create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('superadmin'),
                'role' => 'superadmin',
                'remember_token' => Str::random(10),
            ]
        );
        User::query()->create(
            [
                'name' => $faker->firstName(),
                'email' => $faker->email(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]
        );
        // User::query()->create(
        //     [
        //         'name' => 'ABC',
        //         'email' => 'abc@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('123456'),
        //         'role' => 'user',
        //         'remember_token' => Str::random(10),
        //     ]
        // );
    }
}
