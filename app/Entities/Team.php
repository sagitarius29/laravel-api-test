<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'id', 'name', 'short_name', 'tla', 'address', 'phone', 'website', 'email', 'founded', 'club_colors', 'venue'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competitions_teams');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
