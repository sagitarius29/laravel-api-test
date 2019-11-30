<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $table = 'competitions';

    protected $fillable = [
        'name', 'code'
    ];

    public function teams()
    {
        return $this->hasManyThrough(Team::class, 'competitions_teams');
    }
}
