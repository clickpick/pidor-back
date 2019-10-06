<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\PidorOfTheDay;
use Illuminate\Http\Request;

class PidorOfTheDayController extends Controller
{
    public function current() {
        $user = PidorOfTheDay::getCurrent();

        return new UserResource($user);
    }
}
