<?php

use Illuminate\Support\Facades\Auth;
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

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login');

Route::get('logout', 'Auth\LoginController@logout');

//Admin

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
//Route::prefix('admin')->middleware('auth')->group(function() {

    //admin
    Route::get('/',['uses' => 'Admin\IndexController@index','as' => 'adminIndex']);
    //articles
    Route::resource('/articles','Admin\ArticlesController');
    //permissions
    Route::resource('/permissions','Admin\PermissionsController');
    //users
    Route::resource('/users','Admin\UsersController');
    //menus
    Route::resource('/menus','Admin\MenusController');

});



Route::resource('/','IndexController',[
    'only' => ['index'],
    'names' => [
        'index'=>'home'
    ]
    ]);
Route::resource('portfolios','PortfolioController',[

    'parameters' => [

        'portfolios' => 'alias'

    ]
]);

Route::resource('articles','ArticlesController',[
    'parameters'=>[
        'articles' => 'alias'
    ]
]);

Route::get('articles/cat/{cat_alias?}',['uses'=>'ArticlesController@index','as'=>'articlesCat'])->where('cat_alias','[\w-]+');


Route::resource('comment','CommentController',['only'=>['store']]);

Route::match(['get','post'],'/contacts',['uses'=>'ContactsController@index','as'=>'contacts']);






