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
    private function currentUserId()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->id();
        }

        if (Auth::guard('kioskstudent')->check()) {
            return Auth::guard('kioskstudent')->id();
        }

        abort(403, 'Unauthorized: No authenticated user.');
    }

    public function sendMessage(Request $request)
    {
        try {
            $currentUserId = $this->currentUserId();

            $message = ChatMessage::create([
                'sender_id' => $currentUserId,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function fetchMessages($receiverId)
    {
        $currentUserId = $this->currentUserId();

        $messages = ChatMessage::with('sender') // This now works correctly!
            ->where(function ($q) use ($receiverId, $currentUserId) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($receiverId, $currentUserId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Add sender name directly so JS doesn't have to guess
        $messages->transform(function ($msg) {
            $msg->sender_name = $msg->sender?->name 
                ?? $msg->sender?->student_name 
                ?? 'User';
            return $msg;
        });

        return response()->json($messages);
    }
}
