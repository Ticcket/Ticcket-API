<?php

namespace App\Http\Controllers\Ticket\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use Illuminate\Support\Str;
use App\Models\Ticket;

class TicketsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|numeric|exists:users,id',
            'event_id' => 'required|numeric|exists:events,id',
        ]);

        $t = Ticket::where('user_id', $validated['user_id'])->where('event_id', $validated['event_id'])->first();

        if(!empty($t))
            return ApiResponseTrait::sendError('User Has A Ticket Already', 409);

        $validated['token'] = Str::random(10);

        $ticket = Ticket::create($validated);

        $ticket->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$ticket->token}&choe=UTF-8";

        return ApiResponseTrait::sendResponse("Ticket Created Successfully", $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(empty(Ticket::with('event')->find($id)->event))
            return ApiResponseTrait::sendError("Can't Find Event");

        $ticket = Ticket::where('event_id', $id)->where('user_id', auth()->user()->id)->first();

        if(empty($ticket))
            return ApiResponseTrait::sendError("No Ticket Found");

        if(auth()->user()->id != $ticket->user_id)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $ticket->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$ticket->token}&choe=UTF-8";

        return ApiResponseTrait::sendResponse("Ticket Was Found Successfully", $ticket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(empty(Ticket::with('event')->find($id)->event))
            return ApiResponseTrait::sendError("Can't Find Event");

        $ticket = Ticket::where('event_id', $id)->where('user_id', auth()->user()->id)->first();

        if(empty($ticket))
            return ApiResponseTrait::sendError('Unsuccessful Delete');

        if(auth()->user()->id != $ticket->user_id)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $ticket->delete();

        return ApiResponseTrait::sendResponse("Ticket Deleted Successfully", []);
    }
}
