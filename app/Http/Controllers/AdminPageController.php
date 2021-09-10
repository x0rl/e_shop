<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminActionRequest;
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
        if ($year = $request->year) {
        $request->validate([
            'year' => 'integer|min:2020|max:'.date('Y')
        ]);
        } else {
            $year = date('Y');
        }
        return view('e_shop.sales', [
            'year' => $year,
            'sales' => (new ShoppingList())->getSales($year)
        ]);
    }
    public function users() {
        return view('e_shop.admin-panel', [
            'users' => User::paginate(5)
        ]);
    }
    public function updateUsers(AdminActionRequest $request) 
    {
        $targetUser = User::findOrFail($request->id);
        switch ($request->action) {
            case 'ban':
                $this->authorize('ban', $targetUser);
                $targetUser->ban_status = date('d:m:y', time());
                $targetUser->save();
                $message = [
                    'type'=>'success',
                    'text'=>'Пользователь с ником '.$targetUser['name'].' успешно заблокирован'
                ];
                break;
            case 'unban':
                $targetUser->ban_status = null;
                $targetUser->save();
                $message = [
                    'type'=>'success',
                    'text'=>'Пользователь с ником '.$targetUser['name'].' успешно разблокирован'
                ];
                break;
            case 'upToAdmin':
                $this->authorize('changeAdminStatus', User::class);
                $targetUser->status = 'admin';
                $targetUser->save();
                // $adminPanel = new AdminPanel();
                // $adminPanel->admin_id = $targetUser->id;
                // $adminPanel->admin_login = $targetUser->name;
                // $adminPanel->save(); todo!
                $message = [
                    'type'=>'success',
                    'text'=>'Пользователь с ником '.$targetUser['name'].' повышен до админа'
                ];
                break;
            case 'downToUser':
                $this->authorize('changeAdminStatus', User::class);
                Log::critical("Администратор $targetUser->name был снят с должности " . Auth::user()['name'] . '[' . Auth::user()['id'] . ']');
                $targetUser->status = 'user';
                $targetUser->save();
                // if ($user = AdminPanel::where('admin_id', $targetUser->id))
                //   $user->delete(); todo!
                $message = [
                    'type'=>'success',
                    'text'=>'Пользователь с ником '.$targetUser['name'].' понижен до пользователя'
                ];
                break;
            default:
                abort(404, 'huh');
        }
        return back()->with(['message' => $message]);
    }
}
