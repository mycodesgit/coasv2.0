<?php

namespace App\Models\SettingDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $connection = 'settings';
    protected $table = 'chatmessages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function sender()
    {
        // If sender_id matches current web user → assume it's a regular User
        // We'll check both tables safely
        if ($this->sender_id && \App\Models\AdmissionDB\User::where('id', $this->sender_id)->exists()) {
            return $this->belongsTo(\App\Models\AdmissionDB\User::class, 'sender_id');
        }

        // Otherwise, it's probably a Kioskuser
        return $this->belongsTo(\App\Models\EnrollmentDB\Kioskuser::class, 'sender_id');
    }

    /**
     * Optional: Get receiver the same way (useful for future)
     */
    public function receiver()
    {
        if ($this->receiver_id && \App\Models\AdmissionDB\User::where('id', $this->receiver_id)->exists()) {
            return $this->belongsTo(\App\Models\AdmissionDB\User::class, 'receiver_id');
        }

        return $this->belongsTo(\App\Models\EnrollmentDB\Kioskuser::class, 'receiver_id');
    }

    /**
     * Helper: Get sender name safely (for Blade/JS)
     */
    public function getSenderNameAttribute()
    {
        if ($this->sender) {
            return $this->sender->name ?? $this->sender->student_name ?? 'User';
        }
        return 'Unknown User';
    }
}
