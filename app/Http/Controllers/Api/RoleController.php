<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    public function roles()
    {
        $roles = Role::select('id','name')->get();
        return response()->json([
            
            'roles' => $roles,
        ]);
    }
}
