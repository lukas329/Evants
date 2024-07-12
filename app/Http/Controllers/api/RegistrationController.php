<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function index($id)
    {
        $registrations = Registration::with('user')->where('event_id', $id)->get();

        return response()->json($registrations);
    }
}
