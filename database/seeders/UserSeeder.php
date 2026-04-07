<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name'              => 'Abhishek Jha',
            'phone'             => '9004763926',
            'profile_image'     => 'favicon.png',
            'role'              => 'admin',
            'status'            => 1,
            'email'             => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('Coding_Thunder123@'),

            'created_by'        => 1,
            'created_at'        => Carbon::now(),
        ]);

        // User 1
        User::create([
            'name'              => 'Rahul Sharma',
            'phone'             => '9000000001',
            'profile_image'     => 'favicon.png',
            'role'              => 'user',
            'status'            => 1,
            'email'             => 'rahul@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('Coding_Thunder123@'),

            'created_by'        => 1,
            'created_at'        => Carbon::now(),
        ]);

        // User 2
        User::create([
            'name'              => 'Priya Jha',
            'phone'             => '9000000002',
            'profile_image'     => 'favicon.png',
            'role'              => 'user',
            'status'            => 1,
            'email'             => 'priya@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('Coding_Thunder123@'),

            'created_by'        => 1,
            'created_at'        => Carbon::now(),
        ]);

        // User 3
        User::create([
            'name'              => 'Amit Verma',
            'phone'             => '9000000003',
            'profile_image'     => 'favicon.png',
            'role'              => 'user',
            'status'            => 1,
            'email'             => 'amit@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('Coding_Thunder123@'),

            'created_by'        => 1,
            'created_at'        => Carbon::now(),
        ]);
    }
}