<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseHoleSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {

            for ($hole = 1; $hole <= $course->holes; $hole++) {

                $course->courseHoles()->firstOrCreate(
                    [
                        'hole_number' => $hole,
                    ],
                    [
                        'par' => 3,
                    ]
                );

            }
        }
    }
}