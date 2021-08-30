<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class EditProductController extends Controller
{
  public function editPage(Request $request, $productId)
  {
    $targetProduct = Product::findOrFail($productId);
    if ($request->has('delete')) {
      $subCategory = $targetProduct->sub_category_id;
      $targetProduct->delete();
      $request->session()->flash('message', 'Успешно удалено');
      return redirect()->route('showCategory', ['page'=>$subCategory]);
    } 
    elseif ($request->input('name')) {
      $request->validate([
        'name' => 'required|string|max:45',
        'description' => 'required|string|max:1000',
        'price'=>'required|integer|max:999999',
        'quantity' => 'required|integer|max:9999',
      ]);
      $targetProduct->name = $request->input('name');
      $targetProduct->description = $request->input('description');
      $targetProduct->price = $request->input('price');
      $targetProduct->quantity = $request->input('quantity');
      $targetProduct->save();
      $request->session()->flash('message', 'Успешно отредактировано');
      return redirect()->route('showProduct', ['product'=>$productId]);
    }
    return view('e_shop.editProduct', [
      'product'=>$targetProduct,
      "message" => $request->session()->get('message')
    ]);
  }
}
