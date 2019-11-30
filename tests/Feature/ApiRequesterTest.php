<?php

namespace Tests\Feature;

use App\Lib\FootballApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiRequesterTest extends TestCase
{
    /** @test */
    public function it_can_schedule_job_for_requesting_data()
    {
        $token = '33cf91e523374144a22a73708687c07b';
        $footballApi = new FootballApi('v2', $token);

        // List of Competitions
        $data = $footballApi->get('competitions/2000');

        dd($data);

        $this->assertTrue(is_array($data));

        /*// Get competition data
        $data = $footballApi->get('/competitions/2000');

        // Get List of teams
        $data = $footballApi->get('/competitions/2000/teams');

        // Team Information
        $data = $footballApi->get('/teams/758');*/
    }
}
