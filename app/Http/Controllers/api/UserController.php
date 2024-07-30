<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function getMyId()
    {
        $userId = Auth::id();

        return $userId;
    }
}
