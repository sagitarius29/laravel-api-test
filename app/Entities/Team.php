<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'name', 'short_name', 'tla', 'address', 'phone', 'website', 'email', 'founded', 'club_colors', 'venue'
    ];

    public function competitions()
    {
        return $this->hasManyThrough(Competition::class, 'competitions_teams');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
