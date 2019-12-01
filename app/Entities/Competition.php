<?php

namespace App\Entities;

use App\Lib\FootballData;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $table = 'competitions';

    protected $fillable = [
        'id', 'name', 'code',
    ];

    public static function updateFromApi()
    {
        $footballData = new FootballData('v2');
        $data = $footballData->get('competitions');

        foreach ($data['competitions'] as $competition) {
            Competition::updateOrCreate([
                'id'   => $competition['id'],
                'name' => $competition['name'],
                'code' => $competition['code'],
            ]);
        }
    }

    public function teams()
    {
        return $this->hasManyThrough(Team::class, 'competitions_teams');
    }
}
