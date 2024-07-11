<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyEventsController extends Controller
{
    public function index()
    {
        $user = Auth::id();

        Log::info('Authenticated user ID: ' . $user);

        $events = Event::with('sport')
            ->where('organizer_id', $user)
            ->get();
        return response()->json($events);
    }
}
