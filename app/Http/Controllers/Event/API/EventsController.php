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
            'description' => 'required|string|max:255',
            'logo' => 'required|mimes:png,jpg,jpeg|max:10000',
            'start_at' => 'required|date_format:Y-m-d|after:yesterday',
            'end_at' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);


        $user = User::getUserByToken($request->header('Authorization'));
        $validated['creator'] = $user->id;

        $img = $request->file('logo');

        $img_name = time() . '-' . strtolower($validated['title']) . '.' .$img->extension();

        $img->storeAs('/', $img_name, 'events');

        $validated['logo'] = 'storage/events/' . $img_name;


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

        return ApiResponseTrait::sendResponse('Got Event Successfully', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        //
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

        return $event->delete();

    }
}
