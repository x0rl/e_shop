<?php

namespace App\Http\Controllers;

use Faker\Provider\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditAddressRequest;
use App\Models\Addresses;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\shopCart;
use App\Models\Review;
use App\Models\ShoppingList;

class PersonalAreaController extends Controller 
{
    public function profile()
    {
        return view('e_shop.profile', [
            'emailVerified' => Auth::user()['email_verified_at'] ?? false,
            'address' => Auth::user()->getAddress()
        ]);
    }
    public function reviews() 
    {
        return view('e_shop.personal-area-reviews', [
            'userReviews' => Review::where('user_id', Auth::user()['id'])->paginate(10)
        ]);
    }
    public function shoppingList() 
    {
        $shoppingList = ShoppingList::where('user_id', Auth::user()['id'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        return view('e_shop.shopping-list', compact('shoppingList'));
    }
    public function mainPage()
    {
        return view('e_shop.personal-area');
    }
    public function editAddress(EditAddressRequest $request)
    {
        Addresses::updateOrCreate(
            ['user_id' => Auth::user()['id']],
            $request->validated()
        );
        if ($request->expectsJson()) {
            return 'Адрес доставки успешно изменен';
        } else {
            return redirect()->route('profile');
        }
    }
}
?>