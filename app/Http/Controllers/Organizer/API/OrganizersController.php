<?php

namespace App\Http\Controllers\Organizer\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OragnizerRequest;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use App\Models\User;

class OrganizersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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