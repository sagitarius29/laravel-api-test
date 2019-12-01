<?php

namespace Tests\Unit;

use App\Lib\Limiter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LimiterTest extends TestCase
{
    public function test_create_limit()
    {
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d H:i:s', '2019-11-30 15:30:20'));

        $perMinute = 5;

        $limit = new Limiter($perMinute);

        $limit->increment();
        $limit->increment();

        $totalCurrentUse = $limit->used();

        $this->assertEquals(2, $totalCurrentUse);
        $this->assertTrue($limit->ifAvailable());

        Carbon::setTestNow(now()->addSeconds(40));

        $limit->increment();
        $limit->increment();
        $limit->increment();

        $this->assertEquals(5, $limit->used());
        $this->assertNotTrue($limit->ifAvailable());

        Carbon::setTestNow(now()->addSeconds(40));

        $this->assertTrue($limit->ifAvailable());

        $limit->increment();

        $this->assertEquals(4, $limit->used());
    }
}
