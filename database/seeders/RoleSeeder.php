<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'mahasiswa',
                'display_name' => 'Mahasiswa',
                'description' => 'Student role with limited access',
            ],
            [
                'name' => 'dosen',
                'display_name' => 'Dosen',
                'description' => 'Lecturer role with moderate access',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator role with full access',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
