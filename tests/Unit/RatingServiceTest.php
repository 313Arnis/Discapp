<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Services\RatingService;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RatingServiceTest extends TestCase
{
    #[TestDox('Ja trases 1000 punktu rezultāts ir 50 un koeficients 10, tad 55 metieni dod 950 punktus')]
    public function test_rating_uses_course_parameters(): void
    {
        $course = new Course();
        $course->rating_1000_score = 50;
        $course->rating_per_throw = 10;

        $this->assertSame(950, app(RatingService::class)->calculateRoundRating($course, 55));
    }

    #[TestDox('Mainot trases 1000 punktu rezultātu, mainās spēlētāja raunda reitings')]
    public function test_changing_base_score_changes_rating(): void
    {
        $course = new Course();
        $course->rating_1000_score = 50;
        $course->rating_per_throw = 10;
        $service = app(RatingService::class);

        $before = $service->calculateRoundRating($course, 55);
        $course->rating_1000_score = 52;
        $after = $service->calculateRoundRating($course, 55);

        $this->assertSame(950, $before);
        $this->assertSame(970, $after);
    }

    #[TestDox('Mainot punktu koeficientu par metienu, mainās spēlētāja raunda reitings')]
    public function test_changing_per_throw_changes_rating(): void
    {
        $course = new Course();
        $course->rating_1000_score = 50;
        $course->rating_per_throw = 10;
        $service = app(RatingService::class);

        $before = $service->calculateRoundRating($course, 55);
        $course->rating_per_throw = 8;
        $after = $service->calculateRoundRating($course, 55);

        $this->assertSame(950, $before);
        $this->assertSame(960, $after);
    }

    #[TestDox('Trasei ar nulles reitinga koeficientu aprēķins tiek noraidīts')]
    public function test_zero_per_throw_is_rejected(): void
    {
        $course = new Course();
        $course->rating_1000_score = 50;
        $course->rating_per_throw = 0;

        $this->expectException(ValidationException::class);
        app(RatingService::class)->calculateRoundRating($course, 55);
    }
}
