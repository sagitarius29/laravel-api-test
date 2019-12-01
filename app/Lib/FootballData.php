<?php

namespace App\Lib;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FootballData
{
    protected $token;
    protected $client;
    protected $baseUri = 'http://api.football-data.org/';
    protected $limiter;

    public function __construct($version, $token = null)
    {
        $this->token = config('football.api_token');
        $this->limiter = new Limiter(config('football.api_per_minute'));
        $this->client = new Client([
            'headers' => [
                'X-Auth-Token' => $this->token
            ],
            'base_uri' => $this->baseUri.$version.'/'
        ]);
    }

    public function get($uri)
    {
        while(!$this->limiter->ifAvailable()) {
            sleep(25);
        }

        $this->limiter->increment();

        $response = $this->client->get($uri);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
