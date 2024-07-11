<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::with('sport')->get();
        return response()->json($events);
    }
    public function show($id)
    {
        $event = Event::with(['sport', 'organizer'])->where('id', $id)->firstOrFail();
        return response()->json($event);
    }
}
