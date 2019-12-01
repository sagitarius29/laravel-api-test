<?php

namespace App\Http\Controllers;

use App\Entities\Competition;
use App\Jobs\UpdateCompetitionsTeams;

class CompetitionController extends Controller
{
    public function index()
    {
        if(Competition::count() == 0) {
            Competition::updateFromApi();
        }

        $competitions = Competition::all();

        return response()->json($competitions);
    }

    public function show(Competition $competition)
    {
        UpdateCompetitionsTeams::dispatch($competition->id);

        $competition->teams;

        return response()->json($competition);
    }
}
