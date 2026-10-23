<?php

namespace Tests\Feature;

use App\Models\Competition;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CompetitionRegistrationTimeTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function makeCompetition(): Competition
    {
        $competition = new Competition();

        $competition->status = 'planned';

        $competition->registration_starts_at =
            Carbon::parse('2026-11-01 10:00:00');

        $competition->registration_ends_at =
            Carbon::parse('2026-11-05 20:00:00');

        return $competition;
    }

    #[TestDox('Pirms reģistrācijas sākuma lietotājam nav atļauts pieteikties sacensībām')]
    public function test_registration_before_start(): void
    {
        Carbon::setTestNow('2026-11-01 09:59:59');

        $this->assertFalse(
            $this->makeCompetition()->isRegistrationOpen()
        );
    }

    #[TestDox('Precīzi reģistrācijas sākuma laikā lietotājam ir atļauts pieteikties')]
    public function test_registration_at_start(): void
    {
        Carbon::setTestNow('2026-11-01 10:00:00');

        $this->assertTrue(
            $this->makeCompetition()->isRegistrationOpen()
        );
    }

    #[TestDox('Reģistrācijas perioda vidū lietotājs var veiksmīgi pieteikties sacensībām')]
    public function test_registration_during_period(): void
    {
        Carbon::setTestNow('2026-11-03 15:00:00');

        $this->assertTrue(
            $this->makeCompetition()->isRegistrationOpen()
        );
    }

    #[TestDox('Precīzi reģistrācijas beigu laikā pieteikšanās vēl ir atļauta')]
    public function test_registration_at_end(): void
    {
        Carbon::setTestNow('2026-11-05 20:00:00');

        $this->assertTrue(
            $this->makeCompetition()->isRegistrationOpen()
        );
    }

    #[TestDox('Vienu sekundi pēc reģistrācijas beigām pieteikšanās tiek aizliegta')]
    public function test_registration_after_end(): void
    {
        Carbon::setTestNow('2026-11-05 20:00:01');

        $this->assertFalse(
            $this->makeCompetition()->isRegistrationOpen()
        );
    }

    #[TestDox('Aktīvām sacensībām reģistrācija nav atļauta arī reģistrācijas laika intervālā')]
    public function test_registration_when_active(): void
    {
        Carbon::setTestNow('2026-11-03 15:00:00');

        $competition = $this->makeCompetition();
        $competition->status = 'active';

        $this->assertFalse(
            $competition->isRegistrationOpen()
        );
    }
}
