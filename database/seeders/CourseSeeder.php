<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'Priekuļi',
            'Pauku Priedes',
            'Lēdurga',
            'Zibeņi',
            'Limbo',
            'Ventspils',
            'Tukums',
            'Zaķusala',
            'Zaķumuiža',
            'Sēja',
            'Palsa',
            'Līgatne',
            'Reiņa Trase',
            'Mežinieki',
            'Vaivari',
            'Balvi',
            'Kandava',
            'Vaidava',
            'Garozas',
            'Jumprava',
        ];

        foreach ($courses as $course) {
            Course::create([
                'name' => $course,
                'holes' => 18,

                // Pagaidām tukšas vērtības.
                // Tās aizpildīsim ar Metrix datiem.
                'rating_1000_score' => 0,
                'rating_per_throw' => 0,
            ]);
        }
    }
}