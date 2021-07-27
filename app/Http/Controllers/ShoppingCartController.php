<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\shopCart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
  public function isProductInShoppingCart($productId) { // todo NOT PUBLIC, PRIVATE. (PageController)
    if (Auth::check()) {
      return shopCart::where('user_id', Auth::user()['id'])->where('product_id', $productId)->first() ? true : false;
    }
    else {
      return in_array($productId, session('userShoppingCart', [])) ? true : false;
    }
  }
  public function show(Request $request) {
    if (Auth::check())
      $shoppingCart = shopCart::where('user_id', Auth::user()['id'])->paginate(5);
    else {
      if ($shoppingCart = $request->session()->get('userShoppingCart'))
        foreach ($shoppingCart as $key=>$productId)
          $shoppingCart[$key] = ['id'=>$productId, 'product'=>Product::find($productId)];
    }
    return view('e_shop.shoppingCart', [
      'shoppingCart'=>$shoppingCart ?? []
    ]);
  }
  public function add(Request $request, $productId) {
    $targetProduct = Product::findOrFail($productId);
    if (self::isProductInShoppingCart($productId)) {
      $request->session()->flash('message', 'Уже в корзине');
      return back();
    }
    if (Auth::check()) {
      $newProduct = new shopCart;
      $newProduct->user_id = Auth::user()['id'];
      $newProduct->product_id = $productId;
      $newProduct->save();
      $request->session()->flash('message', 'Добавлено в корзину!');
      return back();
    }
    else {
      $request->session()->push('userShoppingCart', $productId);
      $request->session()->flash('message', 'Добавлено в корзину'); //todo повторяются
      return back();
    }
    //return redirect()->route('showCategory', ['page'=>$targetProduct->sub_category_id]);
  }
  public function delete(Request $request, $productId) {
    $targetProduct = Product::findOrFail($productId);
    if (!self::isProductInShoppingCart($productId)) {
      $request->session()->flash('message', 'Товара нет в вашей корзине');
      return back();
    }
    if (Auth::check()) {
      shopCart::where('user_id', Auth::user()['id'])->where('product_id', $productId)->delete();
      $request->session()->flash('message', 'Удалено из корзины');
      return back();
    }
    else {
      $userShoppingCart = session()->pull('userShoppingCart', []);
      //if(($key = array_search($id, array_column($userShoppingCart, 'id'))) !== false) {
      $key = array_search($productId, $userShoppingCart);
      unset($userShoppingCart[$key]);
      $request->session()->flash('message', 'Удалено из корзины');
      session(['userShoppingCart'=>$userShoppingCart]); //todo
      return back();
    }
  }
}
