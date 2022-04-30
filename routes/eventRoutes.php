<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|------------------------------------
| Response Resources
|------------------------------------
*/

/*
|------------------------------------
| Controllers
|------------------------------------
*/
use App\Http\Controllers\Event\API\EventsController;

/*
|--------------------------------------------------------------------------
| Event API Routes => http://localhost:8000/api/events
|--------------------------------------------------------------------------
*/

Route::apiResource('/events', EventsController::class);

Route::group(['prefix' => 'events'], function () {

    Route::post('/{id}/logo', [EventsController::class, 'changeLogo'])->name("events.update.logo");

    Route::get('/e/search', [EventsController::class, 'search'])->name('events.search');
});
