<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; // 追加
use Illuminate\Support\Facades\Auth; // 追加

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

Route::get('/home', function () {
    // return view('welcome');
    // ウェブサイトのホームページ（'/'のURL）にアクセスした場合のルート
    if (Auth::check()) { // ログイン状態ならば
        return redirect()->route('products.index');
        // 商品一覧ページ（ProductControllerのindexメソッドが処理）へリダイレクト
    } else { // ログイン状態でなければ
        return redirect()->route('login'); //　ログイン画面へリダイレクト
    }
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
});

// Route::post('/search', 'ProductController@index')->name('search'); // search
