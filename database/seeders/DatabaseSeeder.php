<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'nama'  => 'Haev',
            'email' => 'haev@gmail.com',
            'jabatan' => 'Admin',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'nama'  => 'Navira',
            'email' => 'piaimut@gmail.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'nama'  => 'Andita',
            'email' => 'Anditaimut@gmail.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'nama'  => 'Jojo',
            'email' => 'jojo@gmail.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('adminindo'),
         ]);
    }
}
