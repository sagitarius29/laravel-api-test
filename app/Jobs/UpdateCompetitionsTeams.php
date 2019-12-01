<?php

namespace App\Jobs;

use App\Entities\Competition;
use App\Lib\FootballData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCompetitionsTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $competitionId;
    protected $perMinute;
    protected $delaySeconds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($competitionId)
    {
        $this->competitionId = $competitionId;
        $this->perMinute = env('FOOTBALL_API_PER_MINUTE', 10);
        $this->delaySeconds = (int) ceil(60 / $this->perMinute);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(Competition::count() == 0) {
            Competition::updateFromApi();
        }

        $footballData = new FootballData('v2');

        $data = $footballData->get('competitions/'.$this->competitionId.'/teams');

        if (!is_array($data)) {
            $this->release(60);
        }

        if (isset($data['teams'])) {
            $this->updatedTeams($data['teams']);
        }
    }

    public function updatedTeams(array $teams)
    {
        foreach ($teams as $team) {
            dd($team);
        }
    }
}
