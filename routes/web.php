<?php


Route::get('/', function () {
    return view('welcome');
});

Route::resource('customers', 'CustomersController')->except('show')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // ORDERS
    Route::get('/orders', 'OrdersController@index')->name('orders.index');
    Route::get('/create/order', 'OrdersController@create')->name('order.create');
    Route::post('/create/order', 'OrdersController@store')->name('order.post.create');
    Route::get('/edit/order/{ido}', 'OrdersController@edit')->name('order.edit');
    Route::post('/update/order/{ido}', 'OrdersController@update')->name('order.update');
    Route::delete('/delete/order/{ido}', 'OrdersController@delete')->name('order.delete');
    Route::post('/restore/order/{ido}', 'OrdersController@restore')->name('order.restore');

    // CUSTOMERS
    Route::post('/restore/customer/{idc}', 'CustomersController@restore')->name('customer.restore');

});




