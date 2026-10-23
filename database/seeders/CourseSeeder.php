<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Esošās trases
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

            // Papildu trases
            '9Hill - Deviņkalnu disku golfa parks',
            'Ērgļu disku golfa parks',
            'Mežavoti',
            'Malnova',
            'Ziedoņi',
            'Abuls',
            'Lettes',
            'Riekstukalns',
            'Rezidence Kurzeme',
            'Atpūtas parks Laumas',
            'Valta Birzs',
            'Vilce',
            'UFOGOLF Mežaparks',
            'UFOGOLF Mežaparks Mazais laukums',
            'Turlava',
            'Lauku Kūrorts',
            'PROPARK',
            'Ziediņi',
            'Valmieras Viesturu vidusskolas disku parks',
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(
                ['name' => $course],
                [
                    'holes' => 18,
                    'rating_1000_score' => 0,
                    'rating_per_throw' => 0,
                ]
            );
        }
    }
}
