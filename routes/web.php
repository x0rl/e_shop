<?php

use App\Models\SubCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'PageController@showMainPage');
Route::fallback(function () {
  abort(404);
});
Route::get('/amo', 'AmoCRM@test');
Route::get('/', 'PageController@showMainPage');
Route::get('/about', function() {
  return view('e_shop.about');
});
Route::get('/search', 'SearchController@index');
Route::get('/ShoppingCart', 'ShoppingCartController@show');
Route::get('/ShoppingCart/delete/{productId}', 'ShoppingCartController@delete');
Route::get('/ShoppingCart/add/{productId}', 'ShoppingCartController@add');

Route::middleware('IsUserAdmin')->group(function() {
  Route::get('/editProduct/{productId}', 'EditProductController@editPage');

  Route::get('/admin_panel/users', 'AdminPageController@users');
  Route::get('/newProduct/{subCategory}', 'NewProductController@showAddPage');
  Route::post('/newProduct/add', 'NewProductController@add');
  Route::get('/admin_panel/sales', 'AdminPageController@sales');
});

Route::get('/product/{product}', 'ProductController@showProduct')->name('showProduct');

Route::get('/subCategory/{page}', 'PageController@showCategory')->name('showCategory');

Route::middleware('auth')->group(function() {
  Route::post('/product/{productId}/addReview', 'ProductController@addReview');
  Route::post('/product/{productId}/addComment', 'ProductController@addComment');
  Route::post('/buyProduct', 'BuyProductController@showPurchasePage');
  Route::get('/personal_area/reviews', 'PersonalAreaController@reviews');
  Route::get('/personal_area/shoppingList', 'PersonalAreaController@shoppingList');

  Route::get('/personal_area', 'PersonalAreaController@mainPage');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
