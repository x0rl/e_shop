<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
  public function showAdminPage(Request $request) {
    //if ($targetUser = $_GET['banUser']) {}
    if ($targetId = $request->query('id')) {
      if ($targetId == Auth::user()['id'])
        return redirect('/admin_panel');
      $request->validate([
        'id'=>'required|integer',
        'action'=>['required', 'string', 'regex:#ban|unban|upToAdmin|downToUser#']
      ]);
      $targetUser = User::findOrFail($targetId);
      $action = $request->query('action', null);
      if ($action === 'unban') {
        if ($targetUser['ban_status']) {
          $targetUser->ban_status = null;
          $targetUser->save();
          $message =  ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' успешно разблокирован'];
        }
      }
      elseif ($action === 'ban') {
        if ($targetUser['id'] == Auth::user()['id'] or $targetUser['status'] == 'admin')
          $message = ['type'=>'secondary', 'text'=>'Тут только два правила: нельзя забанить себя (ты долбоеб?) и нельзя забанить админа (ты точно долбоеб)'];
        else {
          $targetUser->ban_status = date('d:m:y', time());
          $targetUser->save();
          $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' успешно заблокирован'];
        }
      }
      elseif ($action === 'upToAdmin' and $targetUser->status != 'admin') {
        $targetUser->status = 'admin';
        $targetUser->save();
        $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' повышен до админа'];
      }
      elseif ($action === 'downToUser' and $targetUser->status != 'user') {
        $targetUser->status = 'user';
        $targetUser->save();
        $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' понижен до пользователя'];
      }
    }
    return view('e_shop.adminPanel', [
      'users'=>User::paginate(5),
      'message'=>$message ?? null
    ]);
  }
}
