<?php

namespace App\Http\Controllers\User\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{

    public function search(Request $request) {
        $validated = $request->validate([
            'name' => 'required_without:email|string',
            'email' => 'required_without:name|string',
            'limit' => 'numaric|max:20',
        ]);
        $limit = isset($validated['limit']) ? $validated['limit'] : 5;
        $field = isset($validated['name']) ? 'name' : 'email';
        // return $validated;

        $users = User::where($field, 'LIKE', "%{$validated[$field]}%")->limit($limit)->get();

        return ApiResponseTrait::sendResponse("", [
            'count' => $users->count(),
            'result' => $users,
        ]);

    }

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
        $orgs = [];
        if (auth()->user()->organizers != null) {
            auth()->user()->organizers->each(function ($o) {
                $o->setAttribute('rating', 'da');
            });
        }

        return ApiResponseTrait::sendResponse("User Organizing Events", auth()->user()->organizers ?? []);
    }

    public function getUserTickets() {
        $tickets = auth()->user()->ticket->each(function ($t) {
            $t->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$t->token}&choe=UTF-8";
            $t->event;
        });

        return ApiResponseTrait::sendResponse("User Tickets", $tickets);
    }
}
