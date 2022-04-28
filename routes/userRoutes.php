<?php

use App\Http\Controllers\Auth\API\AuthController;
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


Route::get('/logout', [AuthController::class, 'logout']);
