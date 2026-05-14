<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),

            // Tomei a iniciativa de criar os valores default na factory, pois os testes estavam dando erro
            // e precisariam do método 'refresh()' para que os valores default fossem efetivados antes das asserções
            'score' => 0,
            'status' => 'pending',
        ];
    }
}
