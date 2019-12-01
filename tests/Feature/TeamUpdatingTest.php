<?php

namespace Tests\Feature;

use App\Jobs\UpdateCompetitionsTeams;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamUpdatingTest extends TestCase
{
    /** @test */
    public function updating_teams_test()
    {
        $job = new UpdateCompetitionsTeams(2000);
        $job->handle();

        //dd(Come);
    }
}
