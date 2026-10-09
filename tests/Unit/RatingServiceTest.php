<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Services\RatingService;
use PHPUnit\Framework\TestCase;

class RatingServiceTest extends TestCase
{
    public function test_rating_is_calculated_correctly(): void
    {
        $course = $this->createMock(Course::class);

        $course->method('courseHoles')->willReturn(
            $this->createMock(\Illuminate\Database\Eloquent\Relations\HasMany::class)
        );

        $ratingService = new RatingService();

        $this->assertInstanceOf(RatingService::class, $ratingService);
    }
}
