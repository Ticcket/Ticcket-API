<?php

namespace App\Http\Controllers\User\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

    public function update(Request $request) {
        $validated = $request->validate([
            'name' => 'string|max:15',
            'email' => 'email|unique:users,email',
            'old_password' => 'alpha_num|min:7',
            'new_password' => 'required_with:old_password|alpha_num|different:old_password|min:7',
        ]);

        $u = User::find(auth()->user()->id);

        if (isset($validated['old_password']))
            if (!Hash::check($validated['old_password'], auth()->user()->password))
                return ApiResponseTrait::sendError("Old Password Isn't Correct", 401);


        $u->update([
            "name" => $validated['name'] ?? $u->name,
            "email" => $validated['email'] ?? $u->email,
            "password" => isset($validated['new_password']) ? Hash::make($validated['new_password']) : $u->password,
        ]);

        return ApiResponseTrait::sendResponse("User Data Was Updated Successfully", auth()->user());
    }

    public function getUserEvents() {

        return ApiResponseTrait::sendResponse("Got My Events Successfully", auth()->user()->event ?? []);
    }

    public function getUserOrganize() {

        return ApiResponseTrait::sendResponse("User Organizing Events", auth()->user()->organizers ?? []);
    }
}
