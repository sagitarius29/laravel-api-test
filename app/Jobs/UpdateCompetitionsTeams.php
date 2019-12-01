<?php

namespace App\Jobs;

use App\Entities\Competition;
use App\Entities\Player;
use App\Entities\Team;
use App\Lib\FootballData;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateCompetitionsTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60*10;
    public $tries = 5;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //dd(config('football.api_token'));
        try {
            $this->footballData = new FootballData('v2');

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
        } catch (ClientException $e) {
            if($e->getResponse()->getStatusCode() == 429) {
                var_dump(Cache::getStore());
                print_r($e->getMessage());
                $this->release(65);
            } elseif($e->getResponse()->getStatusCode() == 403) {
                // Resource is restricted
                return;
            } else {
                throw $e;
            }
        }

    }

    public function updatedTeams(array $teams)
    {
        foreach ($teams as $team) {

            $team = $this->updateOrCreate(Team::class, [
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

            $team->competitions()->sync($this->competitionId);

            if($team->players()->count() == 0) {
                $this->updatePlayers($team->id);
            }
        }
    }

    public function updatePlayers($teamId)
    {
        $data = $this->footballData->get('teams/'.$teamId);

        if(isset($data['squad'])) {
            foreach ($data['squad'] as $person) {
                if($person['role'] === 'PLAYER') {
                    $this->updateOrCreate(Player::class, [
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

    private function updateOrCreate(string $class, array $data)
    {
        try {
            $object = $class::where('id', $data['id'])->first();
            if($object == null) {
                $object = new $class($data);
            } else {
                $object->fill($data);
            }

            $object->save();

        } catch (\Exception $e) {
            dd($class, $data);
        }


        return $object;
    }
}
