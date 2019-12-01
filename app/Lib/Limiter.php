<?php

namespace App\Lib;

use Illuminate\Support\Facades\Cache;

class Limiter
{
    protected $perMinute;

    public function __construct($perMinute)
    {
        $this->perMinute = $perMinute;
    }

    public function increment()
    {
        $currentMinute = now()->format('dHi');
        $keyCounter = $currentMinute;
        $keyTimestamp = 't_'.$currentMinute;

        if(Cache::has($keyCounter)) {
            Cache::increment($keyCounter);
        } else {
            Cache::put($keyCounter, 1, 60);
        }

        Cache::put($keyTimestamp, now()->timestamp, 60);
    }

    public function used()
    {
        $timestamp = now()->timestamp;
        $keyCounter = now()->format('dHi');
        $previousCounter = (string) $keyCounter - 1;

        $total = 0;

        if(Cache::has('t_'.$previousCounter) && Cache::get('t_'.$previousCounter) > $timestamp - 60) {
            $total += Cache::get($previousCounter);
        }

        if(Cache::has($keyCounter)) {
            $total += Cache::get($keyCounter);
        }

        return $total;
    }

    public function ifAvailable(): bool
    {
        return $this->perMinute > $this->used();
    }
}
