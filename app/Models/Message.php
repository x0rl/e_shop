<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'message'
    ];

    public static function getDialogList()
    {
        $in = Message::select('from as userId')
            ->where('to', Auth::user()->id)
            ->groupBy('userId')
            ->latest()
            ->get()
            ->unique('userId');
        $out = Message::select('to as userId')
            ->where('from', Auth::user()->id)
            ->groupBy('userId')
            ->latest()
            ->get()
            ->unique('userId');
        $messages = $in->mergeRecursive($out);
        return $messages->unique('userId');
    }
    /* получает список сообщений с определенным пользователем */
    public static function getDialogWithUser($userId)
    {
        return Message::messageFromUser($userId)
            ->orWhere->messageToUser($userId)
            ->latest()
            ->paginate(10);
    }
    public static function markMessageAsRead($userId)
    {
        session(['messageCount' => null]);
        return Message::where('to', Auth::user()->id)->where('from', $userId)->update(['readed' => true]);
    }
    public static function getMessageCount()
    {
        return Message::where('to', Auth::user()->id)->where('readed', false)->count();
    }

    public function scopeMessageFromUser($query, $targetId)
    {
        return $query->where('from', Auth::user()->id)->where('to', $targetId);
    }
    public function scopeMessageToUser($query, $targetId)
    {
        return $query->where('to', Auth::user()->id)->where('from', $targetId);
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'userId');
    }
    public function fromUser() 
    {
        return $this->hasOne('App\Models\User', 'id', 'from');
    }
    public function toUser() 
    {
        return $this->hasOne('App\Models\User', 'id', 'to');
    }
    public function getMessageAttribute($value)
    {
        return Crypt::decryptString($value);
    }
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = Crypt::encryptString($value);
    }
}
