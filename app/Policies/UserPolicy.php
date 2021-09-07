<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
    public function before(User $user, $ability)
    {
        if ($user->status === 'root') {
            return true;
        }
    }
    public function ban(User $user, User $targetUser)
    {
        if ($user->id == $targetUser->id) {
            return Response::deny('самоубийство грех');
        } elseif ($targetUser->status == 'admin') {
            return Response::deny('Нет прав на выполнение этого действия');
        }
        return true;
    }
    public function changeAdminStatus(User $user)
    {
        return $user->status === 'root' ? true : false;
        //return true;
    }
    public function buyProduct(User $user, Product $product)
    {
        //return ($product->price * $quantity) <= $user->money && $product->quantity >= $quantity;
        return false;
    }
    public function test(User $user)
    {
        return true;
    }
}
