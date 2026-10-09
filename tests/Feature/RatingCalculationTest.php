<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Services\RatingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_round_rating_uses_course_par(): void
    {
        $course = Course::factory()->create();

        $course->courseHoles()->createMany([
            ['hole_number' => 1, 'par' => 3],
            ['hole_number' => 2, 'par' => 3],
            ['hole_number' => 3, 'par' => 3],
        ]);

        $service = new RatingService();

        $rating = $service->calculateRoundRating($course, 9);

        // PAR = 9, 1000-rated rezultāts = 4
        // 1000 + ((4 - 9) * 10) = 950
        $this->assertEquals(950, $rating);
    }
}
