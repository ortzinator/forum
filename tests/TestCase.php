<?php

namespace Tests;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Carbon\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        
        Carbon::setTestNow();
    }

    public function signIn($options = null)
    {
        return $this->actingAs(\App\Models\User::factory()->create($options));
    }

    public function assertExists(Model $model)
    {
        $this->assertTrue($model->exists, 'The given model does not exist');

        return $this;
    }

    public function assertMissing(Model $model)
    {
        $this->assertFalse($model->exists, 'The given model exists');
        return $this;
    }

    public function goForwardADay()
    {
        Carbon::setTestNow(Carbon::now()->addDays(1));

        return $this;
    }
}
