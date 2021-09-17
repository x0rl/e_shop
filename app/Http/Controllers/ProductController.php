<?php

namespace App\Http\Controllers;

use App\Events\NewDiscount;
use App\Http\Requests\AddReviewRequest;
use App\Http\Requests\EditProductRequest;
use App\Jobs\DiscountNotification;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDiscount as MailNewDiscount;

class ProductController extends Controller
{
    public function index(Request $request, $productId) 
    {
        $product = Product::findOrFail($productId);
        $inShoppingList = ShoppingCartController::isProductInShoppingList($productId); //todo
        $inShoppingCart = ShoppingCartController::isProductInShoppingCart($productId);
        $inFavorites = Auth::user()->hasProductInFavorites($productId);
        if ($request->show === 'reviews') {
            $reviews = $product->reviews()->paginate(5); //todo сортировку
            $comments = null; //todo
        } else {
            $comments = $product->comments()->paginate(5); //todo сортировку по оценке (1 или 2 или 3 и т.д), по дате
            $reviews = null;
        }
        return view('e_shop.product-page', compact('product',  'inShoppingCart', 'inShoppingList', 'comments', 'reviews', 'inFavorites'));
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
        if ($targetProduct->discount < $request->discount) {
            DiscountNotification::dispatch($targetProduct);
        }
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
        $comment = Comment::create(['text' => $request->comment]);
        $comment->product_id = $productId;
        $comment->user_id = Auth::user()['id'];
        $comment->save();
        return back();
    }
}
