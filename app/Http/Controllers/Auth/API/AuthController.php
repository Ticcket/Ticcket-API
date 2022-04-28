<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SharedTraits\ApiResponseTrait;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $vaildated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:7'
        ]);

        if(auth()->attempt($vaildated)) {

            $token = auth()->user()->createToken("loginToken")->plainTextToken;

            return ApiResponseTrait::sendResponse('Successful Login', [
                'token' => $token
            ], 201);
        }

        return ApiResponseTrait::sendError('Unsuccessful Login', 401);
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:7',
            're_password' => 'required|alpha_num|min:7|same:password'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken("registerToken")->plainTextToken;

        return ApiResponseTrait::sendResponse('User Was Created Successfully', [
            'id' => $user->id,
            'email' => $validated['email'],
            'token' => $token
        ], 201);
    }

    public function logout() {
        $user = auth()->user();

        $user->tokens()->delete();

        return ApiResponseTrait::sendResponse('Logout Sucesss', [
            'email' => $user->email,
        ]);
    }

}
