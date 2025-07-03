<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 admin
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'name' => 'Admin',
            'phone' => '0811000000',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // 1 manager
        User::create([
            'username' => 'manager',
            'password' => bcrypt('manager123'),
            'name' => 'Manager',
            'phone' => '0811000001',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);

        // 18 karyawan (username: karyawan01...karyawan18, password: karyawan01...karyawan18)
        for ($i = 1; $i <= 18; $i++) {
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            User::create([
                'username' => "karyawan{$num}",
                'password' => bcrypt("karyawan{$num}"),
                'name' => "Karyawan {$num}",
                'phone' => "08110000{$num}",
                'email' => "karyawan{$num}@example.com",
                'role' => 'karyawan',
            ]);
        }
    }
}