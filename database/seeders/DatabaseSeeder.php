<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for presentation.
     */
    public function run(): void
    {
        // Super Admin Account
        User::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@hakai.com',
            'jabatan' => 'Admin',
            'is_super_admin' => true,
            'password' => Hash::make('superadmin123'),
        ]);

        User::create([
            'nama' => 'Anditacantik',
            'email' => 'andita@hakai.com',
            'jabatan' => 'Admin',
            'is_super_admin' => true,
            'password' => Hash::make('anditacantik123'),
        ]);

        // Admin Account
        User::create([
            'nama' => 'Admin Hakai',
            'email' => 'admin@hakai.com',
            'jabatan' => 'Admin',
            'password' => Hash::make('admin123'),
        ]);

        // Karyawan Accounts - Professional dummy data
        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@hakai.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('karyawan123'),
        ]);

        User::create([
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@hakai.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('karyawan123'),
        ]);

        User::create([
            'nama' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@hakai.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('karyawan123'),
        ]);

        User::create([
            'nama' => 'Dewi Lestari',
            'email' => 'dewi.lestari@hakai.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('karyawan123'),
        ]);

        User::create([
            'nama' => 'Rizki Pratama',
            'email' => 'rizki.pratama@hakai.com',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('karyawan123'),
        ]);
    }
}
