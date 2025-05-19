<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Approver 1',
            'email' => 'approver1@approver.com',
            'password' => Hash::make('approver123'),
            'role' => 'approver',
        ]);

        User::create([
            'name' => 'Approver 2',
            'email' => 'approver2@approver.com',
            'password' => Hash::make('approver123'),
            'role' => 'approver',
        ]);
    }
}
