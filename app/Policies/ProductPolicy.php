<?php

namespace App\Policies;

use App\Models\Favorites;
use App\Models\Product;
use App\Models\Review;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function buyProduct(User $user, Product $product, int $quantity) 
    {
        return ($product->price * $quantity) <= $user->money && $product->quantity >= $quantity;
    }
    public function addReview(User $user, Product $product)
    {
        if (! ShoppingList::where('product_id', $product->id)->where('user_id', $user->id)->first()) {
            return Response::deny('Приобретите товар, чтобы оставить отзыв');
        } elseif (Review::where('product_id', $product->id)->where('user_id', $user->id)->first()) {
            return Response::deny('Вы уже оставляли отзыв на данный товар');
        }
        return true;
    }
}
