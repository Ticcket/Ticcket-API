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

    Route::get('/{id}/organizers', [EventsController::class, 'getOrganizers'])->name('events.organizers');

    Route::get('/{id}/announcements', [EventsController::class, 'getEventAnnouncements'])->name('events.announcements');

    Route::get('/e/top', [EventsController::class, 'topEvents'])->name('events.top');
});
