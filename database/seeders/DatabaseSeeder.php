<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'Norādi ADMIN_EMAIL un ADMIN_PASSWORD .env failā.'
            );
        }

        if (strlen($password) < 15) {
            throw new RuntimeException(
                'Administratora parolei jābūt vismaz 15 rakstzīmes garai.'
            );
        }

        User::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => Hash::make($password),
                'role' => 'admin',
                'rating' => 0,
            ]
        );

        $this->command->info(
            'Administratora konts veiksmīgi konfigurēts.'
        );

        $this->call([
            CourseSeeder::class,
            CourseHoleSeeder::class,
        ]);
    }
}
