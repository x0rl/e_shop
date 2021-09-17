<?php

use App\Models\SubCategory;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

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
Route::get('/testCart', function() {
    return 'yes. /testcart!!!!!!!1';
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
    Route::get('/editProduct/{productId}/edit', 'ProductController@edit')->name('editProduct');
    //Route::get('/editProduct/{productId}/editPage', 'ProductController@editPage')->name('productEditPage');
    Route::get('/editProduct/{productId}/editPage', function ($productId) {
        return view('e_shop.edit-product', ['product' => Product::findOrFail($productId)]);
    })->name('productEditPage');
    Route::get('/editProduct/{productId}/delete', 'ProductController@delete')->name('deleteProduct');
    Route::get('/admin_panel/users', 'AdminPageController@users');
    Route::get('/admin_panel/users/update', 'AdminPageController@updateUsers')->name('usersAction');
    Route::get('/newProduct/{subCategory}', 'NewProductController@showAddPage');
    Route::post('/newProduct/add', 'NewProductController@add');
    Route::get('/admin_panel/sales', 'AdminPageController@sales');
});

Route::get('/product/{product}', 'ProductController@index')->name('showProduct');

Route::get('/subCategory/{page}', 'PageController@showCategory')->name('showCategory');

Route::middleware('auth')->group(function() {
    Route::get('/mail', 'MailController@index')->name('mail');
    Route::get('/mail/{to}', 'MailController@dialog')->name('mail.dialog');
    Route::post('/mail/{to}/sendMessage', 'MailController@sendMessage')->name('mail.dialog.sendMessage');
    Route::post('/product/{productId}/addReview', 'ProductController@addReview')->name('addReview');
    Route::post('/product/{productId}/addComment', 'ProductController@addComment');
    Route::post('/product/{productId}/addFavorites', 'FavoriteController@add')->name('addFavorites');
    Route::post('/product/{productId}/deleteFavorites', 'FavoriteController@delete')->name('deleteFavorites');
    Route::post('/buyProduct', 'BuyProductController@index');
    Route::post('/buyProduct/submit', 'BuyProductController@store')->name('SubmitPurchase');
    Route::get('/personal_area/reviews', 'PersonalAreaController@reviews');
    Route::get('/personal_area/shoppingList', 'PersonalAreaController@shoppingList');
    Route::get('/personal_area', 'PersonalAreaController@mainPage');
    Route::get('/personal_area/profile', 'PersonalAreaController@profile')->name('edit.profile');
    Route::post('/personal_area/profile/edit/address', 'PersonalAreaController@editAddress')->name('editAddress');
    Route::get('/profile/{userId}', 'ProfileController@index')->name('profile');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
