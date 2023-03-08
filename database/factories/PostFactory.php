<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create(['role_id' => Role::where('name', 'Contributor')->first()->id]);
        return [
            'user_id' => $user->id,
            'title' => 'smartphn',
            'body' => 'cntent'
        ];
    }
}
