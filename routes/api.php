<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|------------------------------------
| Response Resources
|------------------------------------
*/
use App\Http\Resources\UserResource;
/*
|------------------------------------
| Controllers
|------------------------------------
*/
use App\Http\Controllers\Auth\API\AuthController;
use App\Http\Controllers\Feedback\API\FeedbacksController;
use App\Http\Controllers\SharedTraits\EmailTrait;
use SebastianBergmann\CodeUnit\FunctionUnit;

/*
|--------------------------------------------------------------------------
| API Routes => http://localhost:8000/api/
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

// General One-Use Routes:
Route::middleware("auth:sanctum")->group(Function () {
    Route::post('/feedbacks', [FeedbacksController::class, 'store'])->name('feedbacks.store');
    Route::delete('/feedbacks/{id}', [FeedbacksController::class, 'destroy'])->name("feedbacks.destroy");
});


Route::middleware('api.key')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/test/email', function () {
    EmailTrait::sendEmail();
    return "success";
});
