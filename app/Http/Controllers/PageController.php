<?php
  namespace App\Http\Controllers;
  use App\Http\Controllers\Controller;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Http\Request;
  use App\Models\Product;
  use App\Models\shopCart;
  use App\Models\SubCategory;
  use App\Models\Comment;
  use App\Models\Review;
  use App\Models\ShoppingList;
  use App\Models\Category;
  use App\Models\User;
  //use Illuminate\Routing\Controller as BaseController;

  class PageController extends Controller {
    /* todo валидация
     * todo переделать сообщения через request session()->flash
     */
    private $message = null;
    public function showMainPage(Request $request) {
      return view('e_shop.mainPage');
    }
    public function showCategory(Request $request, $id) {
      if (!SubCategory::find($id))
        abort(404);
      elseif ($request->filled(['orderBy', 'sort'])) {
        $request->validate([
          'orderBy'=>['string', 'bail', 'regex:#id|price|rating|reviews#'],
          'sort'=>['string', 'regex:#asc|desc#']
        ]);
        $products = SubCategory::find($id)
          ->products()
          ->orderBy($request->get('orderBy'), $request->get('sort'))
          ->paginate(10)
          ->withQueryString();
      }
      if (!isset($products))
        $products = SubCategory::find($id)->products()->paginate(10);
      if (!Auth::check())
        $userShoppingCart = $request->session()->get('userShoppingCart', []); //todo
      else
        $userShoppingCart = shopCart::where('user_id', Auth::user()['id'])->pluck('product_id')->all(); //todo в представлении productsListPage теперь лишняя проверка auth::check
      return view('e_shop.productsListPage', [
        'products'=>$products,
        'userShoppingCart'=>$userShoppingCart,
        'subCategory'=>SubCategory::select('name', 'id')->where('id', $id)->first(),
        'message'=>$this->message ?? null
      ]);
    }
  }

