<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;

class NewProduct extends Controller
{
  public function showAddPage($subCategory) 
  {
    return view('e_shop.addProduct', [
      'subCategory'=>SubCategory::find($subCategory)
    ]);
  }
  public function add(Request $request) 
  {
    $request->validate([
      'name'=>'required|string|min:5|max:45',
      'description'=>'required|string|min:5|max:1000',
      'price'=>'required|integer',
      'quantity'=>'required|integer'
    ]);
    $newProduct = new Product();
    $newProduct->name = $request->input('name');
    $newProduct->description = $request->input('description');
    $newProduct->price = $request->input('price');
    $newProduct->quantity = $request->input('quantity');
    $newProduct->sub_category_id = $request->input('subCategory');
    $newProduct->save();
    $request->session()->flash('message', 'Успешно добавлено!'); //todo
    return redirect()->route('showCategory', ['page'=>$request->input('subCategory')]);
  }
}
