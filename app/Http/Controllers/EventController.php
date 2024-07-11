<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Display a listing of events
    public function index()
    {
        return view('events.index');
    }
    public function create()
    {
        return view('events.create');
    }

    // Store a newly created event in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'type' => 'required|in:private,public',
            'price' => 'nullable|numeric|min:0',
            'sport_id' => 'nullable|exists:sports,id'
        ]);

        $event = new Event($request->all());
        $event->organizer_id = Auth::id(); // Set the organizer_id to the current user's ID
        $event->save();

        return redirect()->route('events.index')->with('success', 'Event created successfully.');

    }

    // Display the specified event
    public function show($id)
    {
        return view("events.detail");
    }

    // Update the specified event in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|required|date',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }

    // Remove the specified event from storage
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(null, 204);
    }

    public function join($event_id)
    {
        $user = Auth::user();

        // Check if user is already registered for the event
        $existingRegistration = Registration::where('id', $event_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.index')->with('error', 'You have already joined this event.');
        }

        // Register the user for the event
        Registration::create([
            'event_id' => $event_id,
            'user_id' => $user->id,
            'registration_date' => now(),
            'payment_status' => 'free', // Adjust based on your logic
        ]);

        return redirect()->route('events.index')->with('success', 'You have successfully joined the event.');
    }

    public function myEvents()
    {
        return view('events.my-events');
    }
}
