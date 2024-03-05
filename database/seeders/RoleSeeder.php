<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'super-admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'shop-sales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'shop-cs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'shop-admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

       $roles = Role::insert($data);
    }
}
