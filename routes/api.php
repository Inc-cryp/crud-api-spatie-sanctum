<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('roles', [RoleController::class, 'roles']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::middleware('role:Admin')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'Users']);
            Route::get('detail/{id}', [UserController::class, 'DetailUser']);
            Route::post('add', [UserController::class, 'registerUser']);
            Route::post('update/{id}', [UserController::class, 'updateUser']);
            Route::delete('delete/{id}', [UserController::class, 'deleteUser']);
        });
    });

    Route::prefix('post')->group(function () {
        Route::get('/', [PostController::class, 'Posts']);
        Route::get('detail/{id}', [PostController::class, 'getDetailPost']);

        Route::middleware('role:Admin,Contributor')->group(function () {
            Route::get('own', [PostController::class, 'OwnPosts']);
            Route::post('create', [PostController::class, 'create']);
            Route::post('update/{id}', [PostController::class, 'update']);
            Route::delete('delete/{id}', [PostController::class, 'delete']);
        });
    });
});