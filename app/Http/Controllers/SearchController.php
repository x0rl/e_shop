<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
  public function index(Request $request) {
    if (! $query=$request->query('name'))
      //return redirect('/');
      return back();
    $result = Product::where('name', 'like', "%$query%")->paginate(10)->withQueryString();
    return view('e_shop.search', [
      'result'=>$result
    ]);
  }
}
