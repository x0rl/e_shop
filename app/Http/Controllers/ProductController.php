<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  public function showProduct(Request $request, $productId) { //todo в отдельный контроллер
    $product = Product::findOrFail($productId);
    if (Auth::check())
      $inShoppingList = ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()
        ? true : false;
    else
      $inShoppingList = false;
    if ($request->get('show') === 'reviews')
      $reviews = $product->reviews()->paginate(5);
    else
      $comments = $product->comments()->paginate(5);
    return view('e_shop.productPage', [
      'product'=>$product,
      'inShoppingCart'=>app('App\Http\Controllers\ShoppingCartController')->isProductInShoppingCart($productId), //todo трейты, наследование, что угодно пожалуйста когда-нибудь
      'isInShoppingList'=>$inShoppingList,
      'comments'=>$comments ?? null,
      'reviews'=>$reviews ?? null,
    ]);
  }
  public function addReview(Request $request, $productId) {
    $product = Product::findOrFail($productId);
    if (! ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])) {
      $request->session()->flash('message', 'Приобретите товар чтобы оставить отзыв');
      return back();
    }
    if (Review::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()) {
      $request->session()->flash('message', 'Вы уже оставляли отзыв на данный товар.
        Отредактировать или удалить отзыв можно в личном кабинете');
      return back();
    }
    $request->validate([]); //todo
    $newReview = new Review();
    $newReview->user_id = Auth::user()['id'];
    $newReview->product_id = $productId;
    $newReview->text = $request->get('text');
    $newReview->rating = $request->get('rating');
    $newReview->save();
    $product->reviews_count = $product->reviews()->count();
    $product->rating = round($product->reviews()->avg('rating'), 1);
    $product->save();
    $request->session()->flash('message', 'Отзыв успешно сохранен');
    return back();
  }
  public function addComment(Request $request, $productId) {
    $request->validate([]); //todo
    $comment = new Comment();
    $comment->product_id = $productId;
    $comment->user_id = Auth::user()['id'];
    $comment->text = $request->input('comment');
    $comment->save();
    //flash?
    return back();
  }
}
