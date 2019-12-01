<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'id', 'name', 'position', 'shirt_number'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
