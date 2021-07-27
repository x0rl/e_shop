<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\shopCart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
  private function isProductInShoppingCart($productId) {
    if (Auth::check()) {
      return shopCart::where('user_id', Auth::user()['id'])->where('product_id', $productId)->first() ? true : false;
    }
    else {
      return in_array($productId, session('userShoppingCart', [])) ? true : false;
    }
  }
  public function add(Request $request, $productId) {
    if ($targetProduct = Product::find($productId)) {
      if (!self::isProductInShoppingCart($productId)) {
        if (Auth::check()) {
          $newProduct = new shopCart;
          $newProduct->user_id = Auth::user('id');
          $newProduct->product_id = $productId;
          $newProduct->save();
          $request->session()->flash('message', 'Добавлено в корзину!');
          return back();
        }
      }
    }
    return back();
    //return redirect()->route('showCategory', ['page'=>$targetProduct->sub_category_id]);

  }
}
