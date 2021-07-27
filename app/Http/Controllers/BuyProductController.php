<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ShoppingList;

class BuyProductController extends Controller
{
    public function showPurchasePage(Request $request) {
      $productId = $request->get('id');
      $targetProduct = Product::findOrFail($productId);
      if (!$request->get('quantity'))
        abort(404);
      if ($request->get('submit')) {
        $request->validate([
          'quantity'=>'integer|max:'.$targetProduct->quantity
        ]);
        if (Auth::user()['money'] < $targetProduct->price*$request->get('quantity') == false) {
          $user = User::find(Auth::user()['id']);
          $user->money -= $targetProduct->price * $request->get('quantity');
          $user->save();
          $targetProduct->quantity -= $request->get('quantity');
          $targetProduct->save();
          $newRowInShoppingList = new ShoppingList();
          $newRowInShoppingList->user_id = Auth::user()['id'];
          $newRowInShoppingList->product_id = $productId;
          $newRowInShoppingList->quantity = $request->get('quantity');
          $newRowInShoppingList->save();
          return redirect('/personal_area/shoppingList');
        }
        else
          $message = ['type'=>'secondary', 'text'=>'У вас недостаточно денег'];
      }
      return view('e_shop.PurchasePage', [
        'product'=>$targetProduct,
        'quantity'=>$request->get('quantity'),
        'message'=>$message ?? null
      ]);
    }
}
