<?php

namespace Database\Factories;


use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'sender_id' => User::factory(), 
            'recipient_id' => User::factory(), 
            'content' => $this->faker->text(200), 
            'is_read' => $this->faker->boolean(30), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
