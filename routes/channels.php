<?php

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('chat.{to}.{from}', function ($user, $to, $from) {
    Log::debug($user->id . ' - ' . $user->name . '; to: ' . $to);
    return true; // todo
    //return (int) $user->id == (int) $to;
});
Broadcast::channel('new-message.{userId}', function ($user, $userId) {
    return $userId == $user->id;
});