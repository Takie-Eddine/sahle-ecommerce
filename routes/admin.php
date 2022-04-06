<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group([
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function(){



//---------------------------ADMIN------------------------//

Route::group(['namespace' => 'Dashboard' , 'middleware' => 'auth:admin' , 'prefix' => 'admin'],function(){


//---------------------------LOGOUT------------------------//
Route::get('logout','LoginController@logout') -> name('admin.logout');
//---------------------------END-LOGOUT------------------------//


//---------------------------DASHBOARD------------------------//

    Route::get('/','DashboardController@index') -> name('admin.dashboard');

//---------------------------END-DASHBOARD------------------------//


//---------------------------SETTINGS------------------------//

    Route::group(['prefix' => 'settings'], function(){


    //---------------------------SHIPPING------------------------//
            Route::get('shepping-methods/{type}','SettingsController@editShippingMethods') -> name('edit.shippings.method');
            Route::put('shepping-methods/{id}','SettingsController@updateShippingMethods') -> name('update.shippings.method');


    //---------------------------END-SHIPPING------------------------//

    });
//---------------------------END-SETTINGS------------------------//

//---------------------------PROFILE------------------------//

    Route::group(['prefix' => 'profile'], function(){

        Route::get('edit','ProfileController@editProfile') -> name('edit.profile');
        Route::put('update','ProfileController@updateProfile') -> name('update.profile');
        //Route::put('update/password','ProfileController@updatePassword') -> name('update.profile.password');

    });
//---------------------------END-PROFILE------------------------//



//---------------------------CATEGORIES------------------------//

    Route::group(['prefix' => 'main_categories'], function(){

        Route::get('/','MainCategoriesController@index') -> name('admin.maincategories');
        Route::get('create','MainCategoriesController@create') -> name('admin.maincategories.create');
        Route::post('store','MainCategoriesController@store') -> name('admin.maincategories.store');
        Route::get('edit/{id}','MainCategoriesController@edit') -> name('admin.maincategories.edit');
        Route::post('update/{id}','MainCategoriesController@update') -> name('admin.maincategories.update');
        Route::get('delete/{id}','MainCategoriesController@destroy') -> name('admin.maincategories.delete');
        //Route::get('changeStatus/{id}','MainCategoriesController@changeStatus') -> name('admin.maincategories.status');


    });


//---------------------------END-CATEGORIES------------------------//


//---------------------------CATEGORIES------------------------//

Route::group(['prefix' => 'sub_categories'], function(){

    Route::get('/','SubCategoriesController@index') -> name('admin.subcategories');
    Route::get('create','SubCategoriesController@create') -> name('admin.subcategories.create');
    Route::post('store','SubCategoriesController@store') -> name('admin.subcategories.store');
    Route::get('edit/{id}','SubCategoriesController@edit') -> name('admin.subcategories.edit');
    Route::post('update/{id}','SubCategoriesController@update') -> name('admin.subcategories.update');
    Route::get('delete/{id}','SubCategoriesController@destroy') -> name('admin.subcategories.delete');
    //Route::get('changeStatus/{id}','MainCategoriesController@changeStatus') -> name('admin.maincategories.status');


});


//---------------------------END-CATEGORIES------------------------//



});


//---------------------------END-ADMIN------------------------//



//---------------------------LOGIN------------------------//
Route::group(['namespace' => 'Dashboard' , 'middleware' => 'guest:admin' , 'prefix' => 'admin'],function(){

    Route::get('login','LoginController@login') -> name('admin.login');
    Route::post('login','LoginController@postLogin') -> name('admin.post.login');
});
//---------------------------END-LOGIN------------------------//


});
