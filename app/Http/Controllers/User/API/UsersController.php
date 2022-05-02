<?php

namespace App\Http\Controllers\User\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;

class UsersController extends Controller
{
    public function uploadPhoto(Request $request) {
        $request->validate([
            'photo' => 'required|mimes:png,jpg,jpeg|max:10000',
        ]);

        $u = auth()->user();

        if(!empty($u->photo))
            deleteImage($u->photo, 'users');

        $validated['photo'] = uploadImage($request->file('photo'), preg_replace('/\s+/', '_', strtolower(auth()->user()->name)), 'users');

        $u->update($validated);

        return ApiResponseTrait::sendResponse("Photo Uploaded Successfully", $validated);
    }
}
