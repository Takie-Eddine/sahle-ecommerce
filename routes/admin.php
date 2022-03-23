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

});


//---------------------------END-ADMIN------------------------//



//---------------------------LOGIN------------------------//
Route::group(['namespace' => 'Dashboard' , 'middleware' => 'guest:admin' , 'prefix' => 'admin'],function(){

    Route::get('login','LoginController@login') -> name('admin.login');
    Route::post('login','LoginController@postLogin') -> name('admin.post.login');
});
//---------------------------END-LOGIN------------------------//


});
