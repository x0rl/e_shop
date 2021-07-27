<?php
  namespace App\Http\Controllers;
  use Faker\Provider\Person;
  use Illuminate\Http\Request;
  use App\Http\Controllers\Controller;
  use Illuminate\Support\Facades\Auth;
  use App\Models\Product;
  use App\Models\shopCart;
  use App\Models\Review;
  use App\Models\ShoppingList;
  
  class PersonalAreaController extends Controller {
    private $message = null;
    public function reviews() {
      return view('e_shop.PersonalAreaReviews', [
        'userReviews'=>Review::where('user_id', Auth::user()['id'])->paginate(10)
      ]);
    }
    public function shoppingCart(Request $request) { //todo повторяется с PageController ShoppingCartService
      if (isset($_GET['delete']))
        PersonalAreaController::deleteFromCart();
      $shoppingCart = Auth::check()
        ? shopCart::where('user_id', Auth::user()['id'])->paginate(5)
        : $request->session()->get('userShoppingCart');

      return view('e_shop.shoppingCart', [
        'shoppingCart'=>$shoppingCart ?? [],
        'message'=>$this->message
      ]);
    }
    public function shoppingList() {
      $shoppingList = ShoppingList::where('user_id', Auth::user()['id'])
        ->orderBy('created_at', 'desc')
        ->paginate(5);
      return view('e_shop.shoppingList', ['shoppingList'=>$shoppingList]);
    }
    public function mainPage() {
      return view('e_shop.personalArea');
    }
    private function deleteFromCart() { //todo wp
      if (Auth::check()) {
        if ($targetRow = shopCart::where('user_id', Auth::user()['id'])->where('product_id', $_GET['delete'])->first()) {
          $targetRow->delete();
          $this->message = ['type'=>'success', 'text'=>'Удалено из корзины'];
        }
        else
          $this->message = ['type'=>'warning', 'text'=>'Данного товара нет в вашей корзине блин'];
      }
      else {
        $userShoppingCart = session()->pull('userShoppingCart', []);
        if(($key = array_search($_GET['delete'], array_column($userShoppingCart, 'id'))) !== false) {
          unset($userShoppingCart[$key]);
          $this->message = ['type'=>'success', 'text'=>'Удалено из корзины'];
        }
        else
          $this->message = ['type'=>'warning', 'text'=>'Товара нет в вашей корзине'];
        //session()->push('userShoppingCart', $userShoppingCart);
        session(['userShoppingCart'=>$userShoppingCart]);
      }
    }
  }
?>