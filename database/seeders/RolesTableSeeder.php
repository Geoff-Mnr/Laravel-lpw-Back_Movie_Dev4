<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Insert the admin role
        DB::table('roles')->insert([
            'name' => 'Admin',
            'description' => 'Admin Role',
            'status' => 'Y',
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert the user role
        DB::table('roles')->insert([
            'name' => 'Utilisateur',
            'description' => 'User Role',
            'status' => 'Y',
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
