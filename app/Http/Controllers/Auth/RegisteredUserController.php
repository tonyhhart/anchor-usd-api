<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return UserResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::query()->create([
            'name'      => "",
            'email'     => $request->get('email'),
            'password'  => Hash::make($request->get('password')),
            'api_token' => Str::random(120),
        ]);


        event(new Registered($user));

        return UserResource::make($user);
    }
}
