<?php

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

Route::get('/login', 'HomeController@index');  
Route::get('/setlang', 'LocalizationController@setlang')->name('setlang');


Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// Route::get('/', function () {
//     return view('pages.index');
// });

Route::get('/', 'HomeController@insta_feed')->name('main'); 


Route::get('/about-us', function () {
    return view('pages.about-us');
});
Route::get('/contact-us', function () {
    return view('pages.contact-us');
});
 

Route::get('/gallery', 'LocalizationController@gallery_img')->name('gallery'); 
   


Route::post('/coontactus_store', 'ContactUsController@store')->name('coontactus_store'); 

    
Auth::routes();
Route::group(['middleware' => 'auth'], function () {

    Route::get('/add-image', 'ImageController@index')->name('add-image'); 
    Route::post('/gallery_image_store', 'ImageController@store')->name('gallery_image_store'); 
    Route::get('/image/edit/{id}', 'ImageController@create');
    Route::get('/image-create', 'ImageController@create');
    Route::get("/image/delete", "ImageController@s_delete")->name('delete_image');

    // Route::group(['middleware' => 'permission:superadmin'], function () {
    //     Route::get('/add-image', function () {
    //         return view('pages.add_image');
    //     });
    // });
    // Route::group(['middleware' => 'permission:admin'], function () {
    //     Route::get('/add-image', function () {
    //         return view('pages.add_image');
    //     });
    // });
});






