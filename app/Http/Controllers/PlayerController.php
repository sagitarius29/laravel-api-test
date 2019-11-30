<?php

namespace App\Http\Controllers;

use App\Entities\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();

        return response()->json($players);
    }
}
