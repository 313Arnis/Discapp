<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Services\RatingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RatingCalculationTest extends TestCase
{
    use RefreshDatabase;

    private function createCourse(
        int $par,
        float $rating1000Score,
        float $ratingPerThrow
    ): Course {
        
        $course = Course::create([
            'name' => 'Testa trase',
            'holes' => 3,
            'rating_1000_score' => $rating1000Score,
            'rating_per_throw' => $ratingPerThrow,
        ]);

     
        $course->courseHoles()->createMany([
            ['hole_number' => 1, 'par' => $par],
            ['hole_number' => 2, 'par' => $par],
            ['hole_number' => 3, 'par' => $par],
        ]);

        return $course;
    }

    #[TestDox('Reitinga aprēķinā tiek izmantoti administratora ievadītie trases parametri')]
    public function test_rating_uses_course_parameters(): void
    {
        $course = $this->createCourse(3, 8, 10);

        $service = new RatingService();

        $rating = $service->calculateRoundRating($course, 9);

        // 1000 + ((8 - 9) * 10) = 990
        $this->assertEquals(990, $rating);
    }

    #[TestDox('Mainot trases reitinga koeficientu, mainās aprēķinātais spēlētāja reitings')]
    public function test_rating_changes_with_coefficient(): void
    {
        $course = $this->createCourse(3, 8, 5);

        $service = new RatingService();

        $rating = $service->calculateRoundRating($course, 9);

        // 1000 + ((8 - 9) * 5) = 995
        $this->assertEquals(995, $rating);
    }

    #[TestDox('Mainot trases PAR vērtības, aprēķins joprojām izmanto administratora norādīto 1000 reitinga rezultātu')]
    public function test_rating_uses_configured_baseline_when_par_changes(): void
    {
        $course = $this->createCourse(4, 8, 10);

        $service = new RatingService();

        $rating = $service->calculateRoundRating($course, 9);

        // PAR = 12, bet 1000 reitinga rezultāts = 8
        // 1000 + ((8 - 9) * 10) = 990
        $this->assertEquals(990, $rating);
    }

    #[TestDox('Labāks spēlētāja rezultāts nodrošina augstāku raunda reitingu')]
    public function test_better_score_gives_higher_rating(): void
    {
        $course = $this->createCourse(3, 8, 10);

        $service = new RatingService();

        $betterRating = $service->calculateRoundRating($course, 7);
        $worseRating = $service->calculateRoundRating($course, 10);

        // 7 metieni = 1010
        // 10 metieni = 980
        $this->assertEquals(1010, $betterRating);
        $this->assertEquals(980, $worseRating);

        $this->assertGreaterThan(
            $worseRating,
            $betterRating
        );
    }
}
