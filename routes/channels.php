<?php

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('users.{userId}', function ($user, $userId) {
    //return (int) $user->id == (int) $userId;
    return true; //todo
});
// Broadcast::channel('users.{id}', function ($user, $userId) {
//     return $userId === ShoppingList::where('product_id', $product->id)->where('user_id', $userId)->user_id;
// });