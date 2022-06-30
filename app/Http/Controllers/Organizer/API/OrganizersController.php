<?php

namespace App\Http\Controllers\Organizer\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OragnizerRequest;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use App\Models\User;
use App\Models\Ticket;
use App\Models\AnonymousTicket as AnTicket;

class OrganizersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \App\Http\Controllers\SharedTraits\ApiResponseTrait
     */
    public function scanTicket(Request $request)
    {
        $validatated = $request->validate([
            'ticket' => "required|string|max:10",
            "event_id" => "required|numeric|exists:events,id",
        ]);

        if(!auth()->user()->organizers()->where('event_id', $validatated['event_id'])->exists())
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $ticket = Ticket::where('token', $validatated['ticket'])->where('event_id', $validatated['event_id'])->first();
        if(empty($ticket)) {
            $ticket = AnTicket::where('token', $validatated['ticket'])->where('event_id', $validatated['event_id'])->first();
            if(empty($ticket))
                return ApiResponseTrait::sendError("Invalid Ticket", 422);
        }

        // ***********************************
        // Give Error If Already Scanned ?
        // ***********************************

        $ticket->update(["scanned" => 1]);

        $res = [
            "event_id" => $ticket->event_id,
            "token" => $ticket->token,
            "user" => $ticket->user ?? [
                "id" => $ticket->id,
                "name" => $ticket->name,
                "email" => $ticket->email,
                "email_verified_at" => null,
                "photo" => "https://ui-avatars.com/api/?background=random&size=200&name=" . urlencode($ticket->name),
                "created_at" => null,
                "updated_at" => null,
            ],
        ];

        return ApiResponseTrait::sendResponse("Scanned Successfully", $res);
    }

    public function makeAnnouncement(Request $request) {

        $validatated = $request->validate([
            'message' => 'required|string',
            'event_id' => 'required|numeric|exists:events,id',
        ]);

        if(!auth()->user()->organizers()->where('event_id', $validatated['event_id'])->exists())
            return ApiResponseTrait::sendError("Permission Denied", 403);

        auth()->user()->announcements()->attach($validatated['event_id'], ["message" => $validatated['message']]);

        return ApiResponseTrait::sendResponse("An Announcement Has Been Added", $validatated);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OragnizerRequest
     * @return \App\Http\Controllers\SharedTraits\ApiResponseTrait
     */
    public function store(OragnizerRequest $request)
    {
        // Organizer Email
        $validatated = $request->validated();

        if (!isEventOwner($request->header("Authorization"), $validatated['event_id']))
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $org =  User::where('email', $validatated['user_email'])->first();
        $is_org = $org->organizers()->where('event_id', $validatated['event_id'])->exists();

        if($is_org)
            return ApiResponseTrait::sendError("Organizer Already Exists", 409);

        $org->organizers()->attach($validatated['event_id']);

        $event = $org->organizers()->where('event_id', $validatated['event_id'])->first();

        return ApiResponseTrait::sendResponse("Organizer Added Successfully", [
            'email' => $org->email,
            'event' => $event->title,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\OragnizerRequest
     * @return \App\Http\Controllers\SharedTraits\ApiResponseTrait
     */
    public function destroy(OragnizerRequest $request)
    {
        // Email
        $validatated = $request->validated();

        if (!isEventOwner($request->header("Authorization"), $validatated['event_id']))
            return ApiResponseTrait::sendError("Permission Denied", 403);


        $org = User::where('email', $validatated['user_email'])->first();
        $is_org = $org->organizers()->where('event_id', $validatated['event_id'])->exists();

        if(!$is_org)
            return ApiResponseTrait::sendError("Organizer Doesn't Exists", 409);

        $org->organizers()->detach();

        return ApiResponseTrait::sendResponse("Event Deleted Successfully", []);
    }
}
