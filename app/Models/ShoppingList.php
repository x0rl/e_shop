<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
  protected $table = 'shopping_list';
  public function product() {
    return $this->hasOne('App\Models\Product', 'id', 'product_id');
  }
  use HasFactory;
}
