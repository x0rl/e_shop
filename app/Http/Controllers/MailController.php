<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MailController extends Controller
{
    public function index()
    {
        $messages = Message::getDialogList();
        return view('e_shop.mail-page', compact("messages"));
    }
    public function dialog($to)
    {
        $targetUser = User::findOrFail($to);
        $messages = Message::getDialogWithUser($to);
        Message::markMessageAsRead($to);
        return view('e_shop.mail-dialog', compact("messages", "targetUser"));
    }
    public function sendMessage(SendMessageRequest $request, $to)
    {
        User::findOrFail($to);
        $message = Message::create($request->validated());
        $message->from = Auth::user()->id;
        $message->to = $to;
        $message->save();
        return back();
    }
}
