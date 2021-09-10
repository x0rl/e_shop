<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request, $productId) 
    {
        $product = Product::findOrFail($productId);
        if (Auth::check()) {
            $inShoppingList = ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()
                ? true : false;
        } else {
            $inShoppingList = false;
        }
        if ($request->show === 'reviews') {
            $reviews = $product->reviews()->paginate(5); //todo сортировку
        } else {
            $comments = $product->comments()->paginate(5); //todo сортировку по оценке (1 или 2 или 3 и т.д), по дате
        }
        return view('e_shop.product-page', [
            'product' => $product,
            'inShoppingCart' => ShoppingCartController::isProductInShoppingCart($productId), 
            'isInShoppingList' => $inShoppingList,
            'comments' => $comments ?? null,
            'reviews' => $reviews ?? null,
        ]);
    }
    public function delete(Request $request, $productId)
    {
        $targetProduct = Product::findOrFail($productId);
        $subCategory = $targetProduct->sub_category_id;
        $targetProduct->delete();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно удалено'
        ]);
        return redirect()->route('showCategory', ['page' => $subCategory]);
    }
    public function edit(EditProductRequest $request, $productId)
    {
        $targetProduct = Product::findOrFail($productId);
        $targetProduct->fill($request->validated());
        $targetProduct->save();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Успешно отредактировано'
        ]);
        return redirect()->route('showProduct', ['product' => $productId]);
    }
    public function addReview(AddReviewRequest $request, $productId) 
    {
        $product = Product::findOrFail($productId);
        $this->authorize('addReview', $product);
        
        $review = Review::create($request->validated());
        $review->user_id = Auth::user()['id'];
        $review->product_id = $productId;
        $review->save();
        $product->reviews_count = $product->reviews()->count();
        $product->rating = round($product->reviews()->avg('rating'), 1);
        $product->save();
        $request->session()->flash('message', [
            'type' => 'success',
            'text' => 'Отзыв успешно сохранен'
        ]);
        return back();
    }
    public function addComment(Request $request, $productId) 
    {
        $request->validate(['comment' => 'required|string|min:5|max:255']);
        $comment = Comment::create($request->validated());
        $comment->product_id = $productId;
        $comment->user_id = Auth::user()['id'];
        $comment->save();
        return back();
    }
}
