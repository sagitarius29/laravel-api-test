<?php

namespace App\Lib;

use GuzzleHttp\Client;

class FootballData
{
    protected $token;
    protected $client;
    protected $baseUri = 'http://api.football-data.org/';

    public function __construct($version, $token = null)
    {
        $this->token = $token ?? env('FOOTBALL_API_TOKEN', '');
        $this->client = new Client([
            'headers' => [
                'X-Auth-Token' => $this->token
            ],
            'base_uri' => $this->baseUri.$version.'/'
        ]);
    }

    public function get($uri)
    {
        $response = $this->client->get($uri);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
