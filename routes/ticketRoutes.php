<?php

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
use App\Http\Controllers\Ticket\API\TicketsController;
/*
|--------------------------------------------------------------------------
| Event API Routes => http://localhost:8000/api/events
|--------------------------------------------------------------------------
*/

Route::apiResource('/tickets', TicketsController::class)->except(['index', 'update']);
