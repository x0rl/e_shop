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
Route::get('/', 'PageController@showMainPage');
Route::get('/about', function() {
  return view('e_shop.about');
});
Route::get('/search', 'SearchController@index');

Route::middleware('IsUserAdmin')->group(function() {
  Route::get('/editProduct/{productId}', 'editProductController@editPage');

  Route::get('/admin_panel', 'adminPageController@showAdminPage');
  Route::get('/newProduct/{subCategory}', 'NewProduct@showAddPage');
  Route::post('/newProduct/add', 'NewProduct@add');
});

Route::get('/product/{product}', 'PageController@showProduct')->name('showProduct');

Route::get('/subCategory/{page}', 'PageController@showCategory')->name('showCategory');
Route::get('/ShoppingCart', 'PersonalAreaController@shoppingCart');

Route::middleware('auth')->group(function() {
  Route::post('/buyProduct', 'BuyProductController@showPurchasePage');
  Route::get('/personal_area/ShoppingCart/add--{product}', 'ShoppingCartController@add');
  Route::get('/personal_area/shoppingCart', 'PersonalAreaController@shoppingCart');
  Route::get('/personal_area/reviews', 'PersonalAreaController@reviews');
  Route::get('/personal_area/shoppingList', 'PersonalAreaController@shoppingList');

  Route::get('/personal_area', 'PersonalAreaController@mainPage');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
