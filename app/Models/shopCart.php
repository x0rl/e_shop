<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class shopCart extends Model{
    //public $timestamps = false;
    protected $table = 'shopping_cart';
    public function product() {
      return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
  }
?>
