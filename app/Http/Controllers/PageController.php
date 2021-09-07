<?php
  namespace App\Http\Controllers;
  use App\Http\Controllers\Controller;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Http\Request;
  use App\Models\shopCart;
  use App\Models\SubCategory;

  class PageController extends Controller {
    public function showMainPage() 
    {
      return view('e_shop.mainPage');
    }
    public function showCategory(Request $request, $id) 
    {
      if (!SubCategory::find($id)) {
        abort(404);
      } elseif ($request->filled(['orderBy', 'sort'])) {
        $request->validate([
          'orderBy'=>['string', 'bail', 'regex:#id|price|rating|reviews#'],
          'sort'=>['string', 'regex:#asc|desc#']
        ]);
        $products = SubCategory::find($id)
          ->products()
          ->orderBy($request->get('orderBy'), $request->get('sort'))
          ->paginate(10)
          ->withQueryString();
      } else {
        $products = SubCategory::find($id)->products()->paginate(10);
      }
      if (!Auth::check()) {
        $userShoppingCart = $request->session()->get('userShoppingCart', []); //todo
      } else {
        $userShoppingCart = shopCart::where('user_id', Auth::user()['id'])->pluck('product_id')->all(); //todo в представлении productsListPage теперь лишняя проверка auth::check
      }
      return view('e_shop.productsListPage', [
        'products'=>$products,
        'userShoppingCart'=>$userShoppingCart,
        'subCategory'=>SubCategory::select('name', 'id')->where('id', $id)->first()
      ]);
    }
  }

