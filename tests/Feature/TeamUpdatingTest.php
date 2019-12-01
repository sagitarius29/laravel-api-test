<?php

namespace Tests\Feature;

use App\Entities\Team;
use App\Jobs\UpdateCompetitionsTeams;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamUpdatingTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function updating_teams_test()
    {
        $job = new UpdateCompetitionsTeams(2000);
        $job->handle();

        dd(Team::take(3)->get()->toArray());
    }
}
