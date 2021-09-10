<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    protected $table = 'shopping_list';

    public function product() 
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    public function getSales(int $year) 
    {
        return $this->whereRaw('YEAR(created_at) = '.$year)
        ->selectRaw('MONTH(created_at) as month, SUM(price) AS sum')
        ->groupBy('month')
        ->get();
    }
}
