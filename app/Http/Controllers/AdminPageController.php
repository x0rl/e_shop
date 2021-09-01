<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AdminPanel;

class AdminPageController extends Controller
{
  public function sales(Request $request) 
  {
    if ($year = $request->query('year')) {
      $request->validate([
        'year'=>'integer|min:2020|max:'.date('Y')
      ]);
    } else {
      $year = date('Y');
    }
    //$sales = ShoppingList::where('YEAR(created_at)', $year)->get();
    $sales = ShoppingList::whereRaw('YEAR(created_at) = '.$year)
      ->selectRaw('MONTH(created_at) as month, SUM(price) AS sum')
      ->groupBy('month')
      ->get();
    return view('e_shop.sales', [
      'year'=>$year,
      'sales'=>$sales ?? null
    ]);
  }
  public function users(Request $request) 
  {
    //if ($targetUser = $_GET['banUser']) {}
    if ($targetId = $request->query('id')) {
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
          $message = ['type'=>'secondary', 'text'=>'Так нельзя'];
        else {
          $targetUser->ban_status = date('d:m:y', time());
          $targetUser->save();
          $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' успешно заблокирован'];
        }
      }
      elseif ($action === 'upToAdmin' and $targetUser->status != 'admin') {
        $targetUser->status = 'admin';
        $targetUser->save();
        $adminPanel = new AdminPanel();
        $adminPanel->admin_id = $targetUser->id;
        $adminPanel->admin_login = $targetUser->name;
        $adminPanel->save();
        $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' повышен до админа'];
      }
      elseif ($action === 'downToUser' and $targetUser->status != 'user' and $targetUser->id !== Auth::user()['id']) {
        Log::critical("Администратор $targetUser->name был снят с должности ".Auth::user()['name'].'['.Auth::user()['id'].']');
        $targetUser->status = 'user';
        $targetUser->save();
        if ($user = AdminPanel::where('admin_id', $targetUser->id))
          $user->delete();
        $message = ['type'=>'success', 'text'=>'Пользователь с ником '.$targetUser['name'].' понижен до пользователя'];
      }
    }
    //Log::critical('from AdminPageController', ['id'=>Auth::user()['id']]);
    return view('e_shop.adminPanel', [
      'users'=>User::paginate(5),
      'message'=>$message ?? null
    ]);
  }
}
