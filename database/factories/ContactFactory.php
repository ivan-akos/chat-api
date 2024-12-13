<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(), 
            'contact_id' => User::factory(), 
            'accepted' => $this->faker->boolean, 
        ];
    }

    /**
     * Indicate that the contact request is accepted.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'accepted' => true, 
            ];
        });
    }

    /**
     * Indicate that the contact request is not accepted.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notAccepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'accepted' => false, 
            ];
        });
    }
}