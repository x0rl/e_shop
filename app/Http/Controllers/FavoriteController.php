<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function add(Request $request, $productId)
    {
        $request->validate(['action' => 'regex:#addFavorites#']);
        if ($request->user()->hasProductInFavorites($productId)) {
            abort(403, 'ola');
        }

        $newFavorite = new Favorites();
        $newFavorite->user_id = Auth::user()->id;
        $newFavorite->product_id = $productId;
        $newFavorite->save();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно добавлено'
        ]);

        return back();
    }
    public function delete(Request $request, $productId)
    {
        $request->validate(['action' => 'regex:#deleteFavorites#']);
        if (! $request->user()->hasProductInFavorites($productId)) {
            abort(403, 'i think u cant');
        }
        
        Favorites::where('user_id', $request->user()->id)->where('product_id', $productId)->delete();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно удалено'
        ]);
        
        return back();
    }
}
