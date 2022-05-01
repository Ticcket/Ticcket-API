<?php

namespace App\Http\Controllers\Event\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use App\Models\Organizer;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::paginate(10);

        return $events;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string',
            'limit' => 'numaric|max:20',
        ]);

        $limit = isset($validated['limit']) ? $validated['limit'] : 5;

        $events = Event::where('title', 'LIKE', "%{$validated['search']}%")->orWhere('description', 'LIKE', "%{$validated['search']}%")->limit($limit)->get();

        return ApiResponseTrait::sendResponse("", [
            'count' => $events->count(),
            'result' => $events,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:255|min:10',
            'logo' => 'required|mimes:png,jpg,jpeg|max:10000',
            'start_at' => 'required|date_format:Y-m-d|after:yesterday',
            'end_at' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);


        $user = User::getUserByToken($request->header('Authorization'));
        $validated['creator'] = $user->id;

        $validated['logo'] = uploadImage($request->file('logo'), $validated['title']);


        DB::beginTransaction();
        try {
            $event = Event::create($validated);

            Organizer::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();

            return ApiResponseTrait::sendError('Unable To Proceed', 500);
        }



        return ApiResponseTrait::sendResponse('Event Was Created Successfully', $event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);

        if(empty($event))
            return ApiResponseTrait::sendError("Can't Find Event");

        return ApiResponseTrait::sendResponse('Got Event Successfully', $event);
    }

    public function topEvents(Request $request) {
        $validated = $request->validate([
            'limit' => 'numeric|max:10'
        ]);

        $limit = isset($validated['limit']) ? $validated['limit'] : 5;

        // select * from events inner join feedbacks on events.id = feedbacks.event_id order by rating DESC limit 10;
        $topEvents = Event::join('feedbacks', 'events.id', '=', 'feedbacks.event_id')->orderBy('rating', 'DESC')->limit($limit)->get();

        return ApiResponseTrait::sendResponse('Get Top Events', $topEvents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if(empty($event))
            return ApiResponseTrait::sendError("Can't Find Event");

        if (auth()->user()->id != $event->creator)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $validated = $request->validate([
            'title' => 'string|max:100',
            'description' => 'string|max:255|min:10',
            'start_at' => 'date_format:Y-m-d|after:yesterday',
            'end_at' => 'date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $event->update($validated);

        return ApiResponseTrait::sendResponse('Event Updated Successfully', []);
    }

    public function changeLogo(Request $request, $id) {

        $event = Event::find($id);

        if(empty($event))
            return ApiResponseTrait::sendError("Can't Find Event");

        if (auth()->user()->id != $event->creator)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $request->validate([
            'logo' => 'required|mimes:png,jpg,jpeg|max:10000',
        ]);

        $validated['logo'] = uploadImage($request->file('logo'), $event->title);

        $event->update($validated);

        return ApiResponseTrait::sendResponse('Event Logo Updated Successfully', []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        if (empty($event))
            return ApiResponseTrait::sendError('Unsuccessful Delete');


        if (auth()->user()->id != $event->creator)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $event->delete();

        return ApiResponseTrait::sendResponse("Event Deleted Successfully", []);
    }
}
