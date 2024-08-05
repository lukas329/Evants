<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Event::with('sport')
            ->where('organizer_id', '!=', $user->id)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc');

        // Apply filters
        if ($request->has('joined') && $request->joined) {
            $query->whereHas('registrations', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('event_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('event_date', '<=', $request->end_date);
        }

        if ($request->has('organizer_id') && $request->organizer_id) {
            $query->where('organizer_id', $request->organizer_id);
        }

        if ($request->has('sport_id') && $request->sport_id) {
            $query->where('sport_id', $request->sport_id);
        }

        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $events = $query->get()->map(function($event) use ($user) {
            $event->has_joined = Registration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->exists();
            return $event;
        });

        return response()->json($events);
    }
    public function show($id)
    {
        $event = Event::with(['sport', 'organizer'])->where('id', $id)->firstOrFail();
        return response()->json($event);
    }
    public function update(Request $request, $id)
    {
         try{
            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date',
                'event_time' => 'required|date_format:H:i',
                'location' => 'required|string|max:255',
                'type' => 'required|in:private,public',
                'price' => 'nullable|numeric|min:0',
                'sport_id' => 'nullable|exists:sports,id'
            ]);


            $event = Event::findOrFail($id);
            $event->update($validatedData);

            // Return a response, could be JSON or a redirect depending on your application
            return response()->json($event);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log the validation errors
            Log::error('Validation error: ', $e->errors());

            // Re-throw the exception to be handled by the framework
            throw $e;
        }

    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
