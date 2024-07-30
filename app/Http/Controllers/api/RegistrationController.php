<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function index($id)
    {
        $registrations = Registration::with('user')->where('event_id', $id)->get();

        return response()->json($registrations);
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
    public function cancelEvent($eventId)
    {
        $userId = Auth::id();

        // Find the registration and delete it
        $registration = Registration::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if ($registration) {
            $registration->delete();
            return response()->json(['message' => 'Successfully cancelled the event']);
        }

        return response()->json(['message' => 'Registration not found'], 400);
    }
}
