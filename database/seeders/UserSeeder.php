<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'gender' => 'L',
            ]
        );

        // Create dosen user
        User::firstOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'role_id' => $dosenRole->id,
                'name' => 'Dosen User',
                'nip' => '198501012010011001',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'gender' => 'L',
                'birth_date' => '1985-01-01',
            ]
        );

        // Create mahasiswa user
        User::firstOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'role_id' => $mahasiswaRole->id,
                'name' => 'Mahasiswa User',
                'nim' => '2021001001',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'gender' => 'P',
                'birth_date' => '2003-05-15',
            ]
        );
    }
}
