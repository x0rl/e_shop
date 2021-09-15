<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingCartRequest;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Models\shopCart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    public function show() 
    {
        if (Auth::check()) {
            $shoppingCart = shopCart::where('user_id', Auth::user()['id'])->paginate(5);
        } elseif ($shoppingCart = session('userShoppingCart')) {
            foreach ($shoppingCart as $key => $productId) {
                $shoppingCart[$key] = [
                    'id' => $productId,
                    'product' => Product::find($productId)
                ];
            }
        }
        return view('e_shop.shopping-cart', [
            'shoppingCart' => $shoppingCart ?? []
        ]);
    }
    public function add(ShoppingCartRequest $request, $productId) 
    {
        if (self::isProductInShoppingCart($productId)) {
            $request->session()->flash('message', [
                'type' => 'warning',
                'text' => 'Уже в корзине'
            ]);
            return back();
        }
        if (Auth::check()) {
            $newProduct = new shopCart;
            $newProduct->user_id = Auth::user()['id'];
            $newProduct->product_id = $productId;
            $newProduct->save();
        } else {
            $request->session()->push('userShoppingCart', $productId);
        }
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Добавлено в корзину'
        ]);
        return back();
    }
    public function delete(ShoppingCartRequest $request, $productId) 
    {
        if (!self::isProductInShoppingCart($productId)) {
            $request->session()->flash('message', ['type' => 'warning', 'text' => 'Товара нет в вашей корзине']);
            return back();
        }
        if (Auth::check()) {
            shopCart::where('user_id', Auth::user()['id'])->where('product_id', $productId)->delete();
        } else {
            $userShoppingCart = session()->pull('userShoppingCart', []);
            //if(($key = array_search($id, array_column($userShoppingCart, 'id'))) !== false) {
            $key = array_search($productId, $userShoppingCart);
            unset($userShoppingCart[$key]);
            session(['userShoppingCart' => $userShoppingCart]); //todo
        }
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Удалено из корзины'
        ]);
        return back();
    }
    public static function isProductInShoppingCart($productId) 
    {
        if (Auth::check()) {
            return shopCart::where('user_id', Auth::user()['id'])->where('product_id', $productId)->first() ? true : false;
        } else {
            return in_array($productId, session('userShoppingCart', [])) ? true : false;
        }
    }
    public static function isProductInShoppingList($productId)
    {
        if (Auth::check()) {
            return ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()
                ? true : false;
        } else {
            return false;
        }
    }
}
