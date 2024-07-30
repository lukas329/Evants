<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($id)
    {
        $senderId = Auth::id();
        $recipientId = $id;

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
