<?php

use App\Http\Controllers\Auth\API\AuthController;
use App\Http\Controllers\User\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| User API Routes => http://localhost:8000/api/user
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return 'success';
});

Route::post('/u/photo', [UsersController::class, 'uploadPhoto'])->name('user.u.photo');

Route::put('/', [UsersController::class, 'update'])->name('user.update');

Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
