<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait UserHelper
{
    public function createUser($role)
    {
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => Hash::make('passw123'),
            'role_id' => Role::where('name', $role)->first()->id
        ]);
        return $user;
    }
}
