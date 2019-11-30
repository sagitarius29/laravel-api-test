<?php

namespace Tests\Feature;

use App\Entities\Player;
use App\Entities\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_players()
    {
        factory(Team::class, 3)->create();
        factory(Player::class, 20)->create();

        $this->get('/players')
            ->assertSuccessful()
            ->assertJsonCount(20);
    }
}
