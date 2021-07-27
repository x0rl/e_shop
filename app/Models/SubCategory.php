<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
  public $timestamps = false;
  protected $table = 'sub_categories';
  public function products() {
    return $this->hasMany(Product::class);
  }
}
