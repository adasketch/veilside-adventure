<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'username' => 'adminveil',
            // Password wajib di-hash agar aman
            'password' => Hash::make('veiladv01'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opsional: Tambahkan 1 user biasa untuk tes
        DB::table('users')->insert([
            'name' => 'Pengunjung',
            'username' => 'user',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
