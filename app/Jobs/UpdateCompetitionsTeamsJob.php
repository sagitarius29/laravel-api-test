<?php

namespace App\Jobs;

use App\Entities\Competition;
use App\Entities\Player;
use App\Entities\Team;
use App\Lib\FootballData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCompetitionsTeamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $competitionId;
    protected $footballData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($competitionId)
    {
        $this->competitionId = $competitionId;
        $this->footballData = new FootballData('v2');;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Competition::count() == 0) {
            Competition::updateFromApi();
        }

        $data = $this->footballData->get('competitions/'.$this->competitionId.'/teams');

        if (! is_array($data)) {
            $this->release(60);
        }

        if (isset($data['teams'])) {
            $this->updatedTeams($data['teams']);
        }
    }

    public function updatedTeams(array $teams)
    {
        foreach ($teams as $team) {
            $team = Team::updateOrCreate([
                'id'          => $team['id'],
                'name'        => $team['name'],
                'short_name'  => $team['shortName'],
                'tla'         => $team['tla'],
                'address'     => $team['address'],
                'phone'       => $team['phone'],
                'website'     => $team['website'],
                'email'       => $team['email'],
                'founded'     => $team['founded'],
                'club_colors' => $team['clubColors'],
                'venue'       => $team['venue'],
            ]);

            $team->competitions()->attach($this->competitionId);

            $this->updatePlayers($team->id);
        }
    }

    public function updatePlayers($teamId)
    {
        $data = $this->footballData->get('teams/'.$teamId);

        if(isset($data['squad'])) {
            foreach ($data['squad'] as $person) {
                if($person['role'] === 'PLAYER') {
                    Player::updateOrCreate([
                        'id' => $person['id'],
                        'name' => $person['name'],
                        'position' => $person['position'],
                        'shirt_number' => $person['shirtNumber'],
                        'team_id' => $teamId
                    ]);
                }
            }
        }
    }
}
