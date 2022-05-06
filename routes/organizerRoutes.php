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
use App\Http\Controllers\Organizer\API\OrganizersController;
/*
|--------------------------------------------------------------------------
| Organizer API Routes => http://localhost:8000/api/organizers
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'organizers'], function (){
    Route::post('/', [OrganizersController::class, 'store']);
    Route::delete('/', [OrganizersController::class, 'destroy']);
    Route::post('/scan', [OrganizersController::class, 'scanTicket']);
    Route::post('/announcement', [OrganizersController::class, 'makeAnnouncement']);
});
