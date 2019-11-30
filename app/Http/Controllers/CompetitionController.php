<?php

namespace App\Http\Controllers;

use App\Entities\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::all();

        return response()->json($competitions);
    }

    public function show(Competition $competition)
    {
        return response()->json($competition);
    }
}
