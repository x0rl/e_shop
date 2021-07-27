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
    public function shoppingList() {
      $shoppingList = ShoppingList::where('user_id', Auth::user()['id'])
        ->orderBy('created_at', 'desc')
        ->paginate(5);
      return view('e_shop.shoppingList', ['shoppingList'=>$shoppingList]);
    }
    public function mainPage() {
      return view('e_shop.personalArea');
    }
  }
?>