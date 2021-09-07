<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  public function showProduct(Request $request, $productId) 
  {
    $product = Product::findOrFail($productId);
    if (Auth::check()) {
      $inShoppingList = ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()
        ? true : false;
    } else {
      $inShoppingList = false;
    }
    if ($request->get('show') === 'reviews') {
      $reviews = $product->reviews()->paginate(5); //todo сортировку
    } else {
      $comments = $product->comments()->paginate(5); //todo сортировку по оценке (1 или 2 или 3 и т.д), по дате
    }
    return view('e_shop.productPage', [
      'product'=>$product,
      'inShoppingCart'=>ShoppingCartController::isProductInShoppingCart($productId), 
      'isInShoppingList'=>$inShoppingList,
      'comments'=>$comments ?? null,
      'reviews'=>$reviews ?? null,
    ]);
  }
  public function addReview(AddReviewRequest $request, $productId) 
  {
    $product = Product::findOrFail($productId);
    $this->authorize('addReview', $product);
    $newReview = new Review();
    $newReview->user_id = Auth::user()['id'];
    $newReview->product_id = $productId;
    $newReview->text = $request->get('text');
    $newReview->rating = $request->get('rating');
    $newReview->save();
    $product->reviews_count = $product->reviews()->count();
    $product->rating = round($product->reviews()->avg('rating'), 1);
    $product->save();
    $request->session()->flash('message', ['type' => 'success', 'text' => 'Отзыв успешно сохранен']);
    return back();
  }
  public function addComment(Request $request, $productId) 
  {
    $request->validate(['comment' => 'required|string|min:5|max:255']);
    $comment = new Comment();
    $comment->product_id = $productId;
    $comment->user_id = Auth::user()['id'];
    $comment->text = $request->input('comment');
    $comment->save();
    return back();
  }
  public function edit(Request $request, $productId)
  {
    $targetProduct = Product::findOrFail($productId);
    if ($request->has('delete')) {
      $subCategory = $targetProduct->sub_category_id;
      $targetProduct->delete();
      $request->session()->flash('message', ['type' => 'success', 'text' => 'Успешно удалено']);
      return redirect()->route('showCategory', ['page'=>$subCategory]);
    } 
    elseif ($request->input('name')) {
      $request->validate([
        'name' => 'required|string|max:45',
        'description' => 'required|string|max:1000',
        'price'=>'required|integer|max:999999',
        'quantity' => 'required|integer|max:9999',
      ]);
      $targetProduct->name = $request->input('name');
      $targetProduct->description = $request->input('description');
      $targetProduct->price = $request->input('price');
      $targetProduct->quantity = $request->input('quantity');
      $targetProduct->save();
      $request->session()->flash('message', ['type' => 'success', 'text' => 'Успешно отредактировано']);
      return redirect()->route('showProduct', ['product'=>$productId]);
    }
    return view('e_shop.editProduct', [
      'product'=>$targetProduct
    ]);
  }
}
