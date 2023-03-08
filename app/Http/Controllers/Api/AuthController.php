<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBioRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Role;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // validate
        $emailIsExists = User::where('email', $request->email)->first();
        if (!is_null($emailIsExists)) {
            return response()->json(['message' => 'email sudah ada'], 400);
        }

        $defaultRole = Role::where('name', 'Default')->first();
        $password = Hash::make($request->password);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $defaultRole->id
        ]);
        return response()->json(['message' => 'Sukses register']);
    }

    
    public function login(LoginRequest $request)
    {
        // validate email
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            return response()->json(['message' => 'Email not found'], 400);
        }

        // validate passwrd
        $passwordCheck = password_verify($request->password, $user->password);
        if ($passwordCheck) {
            $token = $user->createToken('login');
            return response()->json(['token' => $token->plainTextToken]);
        }
        return response()->json(['message' => 'Wrong password'], 400);
    }

   
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $passwordCheck = password_verify($request->old_password, $user->password);
        if ($passwordCheck) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json(['message' => 'Success change password']);
        }
        return response()->json(['message' => 'Wrong old password'], 400);
    }

   
    public function profile()
    {
        $user = auth()->user();
        return response()->json((new UserTransformer())->transform($user));
    }
}
