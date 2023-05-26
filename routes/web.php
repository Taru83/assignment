<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::post('/login', 'RegisterController@redirectPath')->name('login');
// Route::get('/login', 'RegisterController@create')->name('login');
// Route::post('/login', 'RegisterController@create')->name('login');
// ログアウト
Route::get('/logout', 'HomeController@getLogout')->name('logout');
// 一覧画面
Route::get('/list', 'ProductController@index')->name('list');
// 詳細画面
Route::get('list/show/{id}', 'ProductController@show')->name('show');
// 削除
Route::delete('destroy/{id}', 'ProductController@destroy')->name('destroy');
// 新規作成画面
Route::get('list/add', 'ProductController@add')->name('add');
// 保存
Route::post('list/add', 'ProductController@create')->name('create');
// 編集画面
Route::get('edit/{id}', 'ProductController@edit')->name('edit');
// 更新
Route::patch('edit/{id}', 'ProductController@update')->name('update');
// メーカー一覧
Route::get('company', 'CompanyController@index');
// メーカー追加
Route::get('company/add', 'CompanyController@add');
Route::post('company/add', 'CompanyController@create');
