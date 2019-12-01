<?php

namespace Tests\Feature;

use App\Entities\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CompetitionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_competitions()
    {
        factory(Competition::class, 10)->create();

        $this->get('/competitions')
            ->assertSuccessful()
            ->assertJsonCount(10);
    }

    /** @test */
    public function it_can_get_detais_of_a_competition()
    {
        Queue::fake();

        $competition = factory(Competition::class)->create();

        $this->get('/competitions/' . $competition->id)
            ->assertSuccessful()
            ->assertJson($competition->toArray());

        //Competition not exists
        $this->get('/competitions/' . ($competition->id + 1))
            ->assertStatus(404)
            ->assertJsonStructure([
                'message', 'code'
            ]);
    }
}
