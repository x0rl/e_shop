<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbsentResponsibleAdmin;
use App\Models\AdminPanel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NewProductController extends Controller
{
  public function showAddPage($subCategory) 
  {
    return view('e_shop.addProduct', [
      'subCategory'=>SubCategory::find($subCategory)
    ]);
  }
  public function add(AddProductRequest $request) 
  {
    $targetSubCategory = (int) $request->input('subCategory');
    Gate::authorize('addProduct', $targetSubCategory);
    $newProduct = new Product();
    $newProduct->name = $request->input('name');
    $newProduct->description = $request->input('description');
    $newProduct->price = $request->input('price');
    $newProduct->quantity = $request->input('quantity');
    $newProduct->sub_category_id = $request->input('subCategory');
    $newProduct->save();
    $request->session()->flash('message', ['type' => 'success', 'text' => 'Успешно добавлено!']);
    // if (!AdminPanel::where('responsible_category', $newProduct->sub_category_id)->first()) //todo выключить двухфакторку (либо фикс метода) и расскомментировать
    //   Mail::to(env('ADMIN_EMAIL'))->queue(new AbsentResponsibleAdmin(Auth::user(), $newProduct, $_SERVER['SERVER_NAME'].'/product/'.$newProduct->id));
    return redirect()->route('showCategory', ['page' => $targetSubCategory]);
  }
}
