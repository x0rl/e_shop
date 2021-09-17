<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($userId)
    {
        if (Auth::user()->id == $userId) {
            return redirect()->route('edit.profile');
        }
        $user = User::findOrFail($userId);
        return view('e_shop.profile', compact("user"));
    }
}
