<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@discapp.lv',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'rating' => 1000,
            ]
        );

        $this->command->info('Admin lietotājs izveidots: admin@discapp.lv / admin123');

        $this->call([
            CourseSeeder::class,
            CourseHoleSeeder::class,
        ]);
    }
}