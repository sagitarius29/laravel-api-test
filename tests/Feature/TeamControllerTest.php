<?php

namespace Tests\Feature;

use App\Entities\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_teams()
    {
        $this->withoutExceptionHandling();
        factory(Team::class, 10)->create();

        $this->get('/teams')
            ->assertSuccessful()
            ->assertJsonCount(10);
    }

    public function it_can_get_details_of_a_team()
    {
        $team = factory(Team::class)->create();

        $this->get('/teams/' . $team->id)
            ->assertSuccessful()
            ->assertJson($team->toArray());

        //Competition not exists
        $this->get('/teams/' . ($team->id + 1))
            ->assertStatus(404)
            ->assertJsonStructure([
                'message', 'code'
            ]);
    }
}
