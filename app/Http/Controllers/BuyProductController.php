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
        $quantity = $request->quantity;
        $targetProduct = Product::findOrFail($request->product_id);
        if ($request->user()->cannot('buyProduct', [$targetProduct, $quantity])) {
            abort(403, 'wat r u doing');
        }
        $request->user()->money -= $targetProduct->price * $request->get('quantity');
        $request->user()->save();
        $targetProduct->quantity -= $request->get('quantity');
        $targetProduct->save();
        $newRow = ShoppingList::create($request->validated());
        $newRow->user_id = Auth::user()['id'];
        $newRow->save();
        BuyProduct::dispatch($targetProduct);
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно приобретено!'
        ]);
        return redirect('/personal_area/shoppingList');
    }
    public function index(Request $request)
    {
        return view('e_shop.purchase-page', [
            'product' => Product::findOrFail($request->id),
            'quantity' => $request->quantity
        ]);
    }
}
