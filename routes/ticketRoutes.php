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
| Ticket API Routes => http://localhost:8000/api/tickets
|--------------------------------------------------------------------------
*/

Route::apiResource('/tickets', TicketsController::class)->except(['index', 'update']);
