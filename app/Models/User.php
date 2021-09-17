<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable// implements MustVerifyEmail todo
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function isAdmin() 
    {
        return $this->status === 'admin' or $this->status === 'root';
    }
    public function buyProduct(Product $product, int $quantity)
    {
        
    }
    public function getAddress()
    {
        return Addresses::where('user_id', $this->id)->first();
    }
    public function hasProductInFavorites($productId)
    {
        return Favorites::where('user_id', $this->id)->where('product_id', $productId)->first()
            ? true : false;
    }
}
