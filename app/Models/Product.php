<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'discount'
    ];

    public $timestamps = false;

    public function reviews() 
    {
        return $this->hasMany('App\Models\Review');
    }
    public function comments() 
    {
        return $this->hasMany('App\Models\Comment');
    }
    public function subCategory() 
    {
        return $this->hasOne('App\Models\SubCategory', 'id', 'sub_category_id');
    }
    public function getId() 
    {
        return $this->id;
    }
    public function getName() 
    {
        return $this->name;
    }
    public function getPrice() 
    {
        return $this->price;
    }
}
?>
