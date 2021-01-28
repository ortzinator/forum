<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class DatabaseNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DatabaseNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'type' => 'App\Notifications\ThreadWasUpdated',
            'notifiable_id' => function () {
                return Auth::user() ?: User::factory();
            },
            'notifiable_type' => 'App\Models\User',
            'data' => ['foo' => 'bar']
        ];
    }
}
