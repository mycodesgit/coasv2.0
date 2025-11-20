<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\AdmissionDB\User;
use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\KioskUser;

use App\Models\SettingDB\ChatMessage;

class ChatMessageController extends Controller
{
    public function fetchMessages(Request $request)
    {
        $userId = Auth::id(); // current user
        $receiverId = $request->receiver_id;

        if (!$receiverId) {
            return response()->json([]);
        }

        $messages = ChatMessage::where(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)
                  ->where('receiver_id', $userId);
            })
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Send a message to a receiver
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message'     => 'required|string|max:500',
            'receiver_id' => 'required|integer',
        ]);

        $msg = ChatMessage::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        return response()->json($msg);
    }
}
