<?php

namespace App\Http\Controllers\Feedback\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;

class FeedbacksController extends Controller
{
    public function store(Request $request) {

        $validated = $request->validate([
            'rating' => 'required|numeric|max:5',
            'comment' => 'required|string',
            'event_id' => 'required|numeric|exists:events,id',
        ]);

        $f = Feedback::where('user_id', auth()->user()->id)->where('event_id', $validated['event_id'])->first();
        if (!empty($f))
            return ApiResponseTrait::sendError('User Has Ticket Already', 409);

        $validated['user_id'] = auth()->user()->id;

        $feedback = Feedback::create($validated);

        return ApiResponseTrait::sendResponse("Added Feedback Successfully", $feedback);
    }

    public function destroy($id) {
        $feedback = Feedback::find($id); // select * form feebacks where id = $id;

        if (empty($feedback))
            return ApiResponseTrait::sendError("Can't Find Feedback");

        if(auth()->user()->id != $feedback->user_id)
            return ApiResponseTrait::sendError("Permission Denied", 403);

        $feedback->delete(); // delete From feedbacks where id = $id;

        return ApiResponseTrait::sendResponse("Feedback Deleted Successfully", []);
    }
}
