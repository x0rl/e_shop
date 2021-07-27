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
     * todo огромный контроллер. отдельный контроллер для корзины и showProduct
     * todo переделать сообщения через request session()->flash
     */
    private $message = null;
    public function showMainPage(Request $request) {
      return view('e_shop.mainPage');
    }
    private function ShoppingCartService(Request $request, $id, $action) {
      //todo return
      if (!Product::find($id)) {
        $this->message = ['type'=>'secondary', 'text'=>'Данного товара не существует'];
        return false;
      }
      if ($action === 'delFromCart') {
        if (!Auth::check()) {
          $userShoppingCart = session()->pull('userShoppingCart', []);
          if(($key = array_search($id, array_column($userShoppingCart, 'id'))) !== false) {
            unset($userShoppingCart[$key]);
            $this->message = ['type'=>'success', 'text'=>'Удалено из корзины'];
          }
          else
            $this->message = ['type'=>'secondary', 'text'=>'Товара нет в вашей корзине'];
        }
        else {
          if (shopCart::where('user_id', Auth::user()['id'])->where('product_id', $id)->delete())
            $this->message = ['type'=>'success', 'text'=>'Удалено из корзины'];
          else
            $this->message = ['type'=>'secondary', 'text'=>'Товара нет в вашей корзине'];
        }
      }
      elseif ($action === 'addToCart') {
        if (!Auth::check()) {
          if (in_array($id, array_column(session('userShoppingCart', []), 'id')))
            $this->message = ['type'=>'secondary', 'text'=>'Уже в корзине'];
          else {
            $request->session()->push('userShoppingCart', ['id'=>$id, 'product'=>Product::findOrFail($id)]);
            $this->message = ['type'=>'success', 'text'=>'Добавлено в корзину'];
          }
        }
        else {
          if (!shopCart::where('product_id', $id)->where('user_id', Auth::user()['id'])->first()) {
            $product = new shopCart;
            $product->user_id = Auth::user()['id'];
            $product->product_id = $id;
            $product->save();
            $this->message = ['type'=>'success', 'text'=>'Добавлено в корзину'];
          }
          else
            $this->message = ['type'=>'secondary', 'text'=>'Уже в корзине'];
        }
      }
      elseif ($action === 'isInShoppingCart') {
        if (Auth::check()) {
          return shopCart::where('user_id', Auth::user()['id'])
            ->where('product_id', $id)
            ->first() ? true : false;
        }
        else {
          return in_array($id, array_column(session('userShoppingCart', []), 'id')) ? true : false;
        }
      }
    }
    public function showProduct(Request $request, $productId) { //todo в отдельный контроллер
      $product = Product::findOrFail($productId);
      if ($request->input('addReview') and $request->input('rating')) {
        if (!Auth::check())
          return redirect('/register');
        $request->validate([]); //todo
        if (ShoppingList::where('product_id', $productId)
          ->where('user_id', Auth::user()['id'])
          ->first()) {
          if (! Review::where('user_id', Auth::user()['id'])->where('product_id', $productId)->first()) {
            $newReview = new Review();
            $newReview->user_id = Auth::user()['id'];
            $newReview->product_id = $productId;
            $newReview->text = $request->get('addReview');
            $newReview->rating = $request->get('rating');
            $newReview->save();
            $product->reviews_count = $product->reviews()->count();
            $product->rating = round($product->reviews()->avg('rating'), 1);
            $product->save();
          }
          else {
            $this->message = ['type'=>'secondary', 'text'=>'Вы уже оставляли отзыв для данного товара'];
          }
        }
        else
          $this->message = ['type'=>'secondary', 'text'=>'Вы не покупали данный товар'];
      }
      if ($request->filled('action'))
        PageController::shoppingCartService($request, $productId, $request->get('action'));
      if ($request->input('comment')) {
        if (!Auth::check())
          return redirect('/register');
        $request->validate(['comment'=>'string|max:100']); //todo представление ProductPage сделать валидацию на кнопки
        $comment = new Comment();
        $comment->product_id = $productId;
        $comment->user_id = Auth::user()['id'];
        $comment->text = $request->input('comment');
        $comment->save();
      }
      if (Auth::check())
        $inShoppingList = ShoppingList::where('product_id', $productId)->where('user_id', Auth::user()['id'])->first()
          ? true : false;
      else
        $inShoppingList = false;
      if ($request->get('show') === 'reviews')
        $reviews = $product->reviews()->paginate(5);
      else
        $comments = $product->comments()->paginate(5);
      return view('e_shop.productPage', [
        //'product'=>$product,
        'product'=>$product,
        'inShoppingCart'=>PageController::shoppingCartService($request, $productId, 'isInShoppingCart'),
        'isInShoppingList'=>$inShoppingList,
        'comments'=>$comments ?? null,
        'reviews'=>$reviews ?? null,
        'message'=>$this->message
      ]);
    }
    public function showCategory(Request $request, $id) {
      if (!SubCategory::find($id))
        abort(404);
      if ($request->filled(['id', 'action'])) {
        PageController::shoppingCartService($request, $request->get('id'), $request->get('action'));
      }
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
        $userShoppingCart = shopCart::where('user_id', Auth::user()['id'])->pluck('product_id')->all();
      return view('e_shop.productsListPage', [
        'products'=>$products,
        'userShoppingCart'=>$userShoppingCart,
        'subCategory'=>SubCategory::select('name', 'id')->where('id', $id)->first(),
        'message'=>$this->message ?? null
      ]);
    }
  }

