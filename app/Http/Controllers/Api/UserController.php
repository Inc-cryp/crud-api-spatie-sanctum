<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\RegisterMail;
use App\Models\Role;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use Illuminate\Support\Str;
use League\Fractal\Resource\Collection;

/**
 * @group User Management
 *
 * Api's for Admin manage user
 */
class UserController extends Controller
{
    
    public function Users()
    {
        $users = User::orderBy('created_at')->get()->except(auth()->user()->id);
        $fract = new Manager();
        $resource = new Collection($users, new UserTransformer());
        return response()->json($fract->createData($resource)->toArray());
    }

    public function DetailUser($id)
    {
        $user = User::find($id);
        return response()->json((new UserTransformer())->transform($user));
    }

    public function registerUser(RegisterUserRequest $request)
    {
        $emailIsExists = User::where('email', $request->email)->first();
        if (!is_null($emailIsExists)) {
            return response()->json(['message' => 'sudah ada'], 400);
        }

        $password = Str::random(15);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => Role::where('name', 'Default')->first()->id
        ]);
        Mail::to($request->user())->send(new RegisterMail($user, $password));
        return response()->json(['message' => 'Success create user, please check email for login.']);
    }

    public function updateUser($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 400);
        }
        $user->update(request()->except('email'));
        return response()->json(['message' => 'Success update user']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 400);
        }
        $user->delete();
        return response()->json(['message' => 'Success delete user']);
    }
}
