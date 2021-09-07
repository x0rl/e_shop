<?php

namespace App\Http\Controllers;

use App\Events\BuyProduct;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ShoppingList;

class BuyProductController extends Controller
{
  public function store(PurchaseRequest $request) 
  {
    $productId = $request->get('id');
    $quantity = $request->get('quantity');
    $targetProduct = Product::findOrFail($productId);
    if ($request->user()->cannot('buyProduct', [$targetProduct, $quantity])) {
      abort(403, 'wat r u doing');
    }
    $request->user()->money -= $targetProduct->price * $request->get('quantity');
    $request->user()->save();
    $targetProduct->quantity -= $request->get('quantity');
    $targetProduct->save();
    $newRowInShoppingList = new ShoppingList();
    $newRowInShoppingList->user_id = Auth::user()['id'];
    $newRowInShoppingList->product_id = $productId;
    $newRowInShoppingList->quantity = $request->get('quantity');
    $newRowInShoppingList->save();
    BuyProduct::dispatch($targetProduct);
    $request->session()->flash('message', ['type' => 'success', 'text' => 'Успешно приобретено!']);
    return redirect('/personal_area/shoppingList');
  }
  public function index(Request $request)
  {
    return view('e_shop.PurchasePage', [
      'product'=>Product::findOrFail($request->get('id')),
      'quantity'=>$request->get('quantity')
    ]);
  }
}
