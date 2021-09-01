<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbsentResponsibleAdmin;
use App\Models\AdminPanel;
use Illuminate\Support\Facades\Auth;

class NewProductController extends Controller
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
    if (!SubCategory::find($request->input('subCategory'))) {
      $request->session()->flash('message', 'Вы пытаетесь добавить товар в несуществующую категорию!!!');
      return back();
    }
    $newProduct = new Product();
    $newProduct->name = $request->input('name');
    $newProduct->description = $request->input('description');
    $newProduct->price = $request->input('price');
    $newProduct->quantity = $request->input('quantity');
    $newProduct->sub_category_id = $request->input('subCategory');
    $newProduct->save();
    $request->session()->flash('message', 'Успешно добавлено!'); //todo
    if (!AdminPanel::where('responsible_category', $newProduct->sub_category_id)->first())
      Mail::to(env('ADMIN_EMAIL'))->queue(new AbsentResponsibleAdmin(Auth::user(), $newProduct, $_SERVER['SERVER_NAME'].'/product/'.$newProduct->id));
  }
}
