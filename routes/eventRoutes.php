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

Route::resource('/events', EventsController::class)->except(['create', 'edit']);

Route::group(['prefix' => 'events'], function () {
    Route::get('/search', [EventsController::class, 'search'])->name('events.search');
});
