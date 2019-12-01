<?php

namespace App\Lib;

use Illuminate\Support\Facades\Cache;

class Limiter
{
    protected $perMinute;

    public function __construct($perMinute = 10)
    {
        $this->perMinute = $perMinute;
    }

    public function increment()
    {
        $currentMinute = now()->format('YmdHi');
        $keyCounter = $currentMinute;
        $keyTimestamp = 't_'.((int) $currentMinute);

        if(Cache::has($keyCounter)) {
            Cache::increment($keyCounter);
        } else {
            Cache::put($keyCounter, 1, 200);
        }

        Cache::put($keyTimestamp, now()->timestamp, 200);
    }

    public function used()
    {
        $timestamp = now()->timestamp;
        $keyCounter = now()->format('YmdHi');
        $previousCounter = $keyCounter - 1;

        $total = 0;

        if(Cache::has('t_'.$previousCounter) && Cache::get('t_'.$previousCounter) > $timestamp - 60) {
            $total += Cache::get((string) $previousCounter);
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
