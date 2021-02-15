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

    /**
     * Create and sign in a user
     * 
     * @param array|null $options
     * 
     * @return $this
     */
    public function signIn($user = null)
    {
        $user = $user ?: \App\Models\User::factory()->create();
        return $this->actingAs($user);
    }

    /**
     * Assert that the given model exists in the database
     * 
     * @param Model $model
     * 
     * @return $this
     */
    public function assertExists(Model $model)
    {
        $this->assertTrue($model->exists, 'The given model does not exist');

        return $this;
    }

    /**
     * Assert that the given model does not exist in the database
     * 
     * @param Model $model
     * 
     * @return $this
     */
    public function assertMissing(Model $model)
    {
        $this->assertFalse($model->exists, 'The given model exists');
        return $this;
    }

    /**
     * Move time forward a day
     * 
     * @return $this
     */
    public function goForwardADay()
    {
        Carbon::setTestNow(Carbon::now()->addDays(1));

        return $this;
    }
}
