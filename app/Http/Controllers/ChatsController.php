<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        $messages = "";
        try {
            $messages = Message::with(['sender', 'recipient'])->get();
        }catch (Exception $e){
            Log::error($e);
        }
        return $messages;
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $recipientId = $request->input('recipient_id');
        $content = $request->input('content');

        // Log the recipient ID
        Log::info('Sending message', [
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'content' => $content,
        ]);

        try {
            $message = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipientId,
                'content' => $content
            ]);

            return ['status' => 'Message Sent!'];
        } catch (\Exception $e) {
            // Log the error if message creation fails
            Log::error('Failed to send message', [
                'sender_id' => $user->id,
                'recipient_id' => $recipientId,
                'content' => $content,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
    public function fetchMessagesBetween(Request $request)
    {
        $senderId = $request->input('sender_id');
        $recipientId = $request->input('recipient_id');

        $messages = Message::where(function($query) use ($senderId, $recipientId) {
            $query->where('sender_id', $senderId)
                ->where('recipient_id', $recipientId);
        })->orWhere(function($query) use ($senderId, $recipientId) {
            $query->where('sender_id', $recipientId)
                ->where('recipient_id', $senderId);
        })->with(['sender', 'recipient'])->get();

        return response()->json($messages);
    }
}

