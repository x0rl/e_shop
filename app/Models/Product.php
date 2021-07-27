<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Product extends Model{
    public $timestamps = false;
    public function reviews() {
      return $this->hasMany('App\Models\Review');
    }
    public function comments() {
      return $this->hasMany('App\Models\Comment');
    }
    public function subCategory() {
      return $this->hasOne('App\Models\SubCategory', 'id', 'sub_category_id');
    }
  }
?>
