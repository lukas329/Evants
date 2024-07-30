<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $attendedEventsCount = $user->events()->count();
        $registeredEventsCount = $user->registrations()->count();

        $nextEvent = DB::table('events as e')
            ->join('registrations as r', 'e.id', '=', 'r.event_id')
            ->where('r.user_id', Auth::id())
            ->whereRaw("CONCAT(e.event_date, ' ', e.event_time) > NOW()")
            ->orderByRaw("CONCAT(e.event_date, ' ', e.event_time) ASC")
            ->select('e.*')
            ->first();

        return response()->json([
            'attendedEventsCount' => $attendedEventsCount,
            'registeredEventsCount' => $registeredEventsCount,
            'nextEvent'=>$nextEvent,
        ]);

    }
}
