<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserInfoResource;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function store(Request $request): UserInfoResource
    {
        $info = current_user()->info()->updateOrCreate([], $request->all());

        return UserInfoResource::make($info);
    }
}
