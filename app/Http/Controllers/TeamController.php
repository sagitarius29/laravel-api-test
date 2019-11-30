<?php

namespace App\Http\Controllers;

use App\Entities\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();

        return response()->json($teams);
    }

    public function show(Team $team)
    {
        return response()->json($team);
    }
}
