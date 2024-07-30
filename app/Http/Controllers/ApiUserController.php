<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function getUser(Request $request)
    {
        return $request->user();
    }
}
