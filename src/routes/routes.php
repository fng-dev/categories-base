<?php

Route::group(['namespace' => 'Fng\CategoryBase\Http\Controllers'], function () {

    //Type routes

    Route::post('/admin/types/create', 'FngTypeController@create');
    Route::put('/admin/types/update/{id}', 'FngTypeController@update');
    Route::get('/admin/types', 'FngTypeController@getAll');
    Route::get('/admin/types/{id}', 'FngTypeController@getById');
    Route::delete('/admin/types/delete/{id}', 'FngTypeController@delete');

    //Category Routes

    Route::post('/admin/categories/create', 'FngCategoryController@create');
    Route::put('/admin/categories/update/{id}', 'FngCategoryController@update');
    Route::get('/admin/categories', 'FngCategoryController@getAll');
    Route::get('/admin/categories/{id}', 'FngCategoryController@getById');
    Route::get('/admin/categories/get/father', 'FngCategoryController@getFather');
    Route::delete('/admin/categories/delete/{id}', 'FngCategoryController@delete');

    //Product Routes

    Route::post('/admin/products/create', 'FngProductController@create');
    Route::put('/admin/products/update/{id}', 'FngProductController@update');
    Route::get('/admin/products', 'FngProductController@getAll');
    Route::get('/admin/products/{id}', 'FngProductController@getById');
    Route::delete('/admin/products/delete/{id}', 'FngProductController@delete');

});
