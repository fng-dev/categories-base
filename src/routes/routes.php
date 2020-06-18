<?php

Route::group(['namespace' => 'Fng\CategoryBase\Http\Controllers'], function () {

    //Type routes

    Route::post('/admin/types/create', 'FngTypeController@create');
    Route::put('/admin/types/update/{id}', 'FngTypeController@update');
    Route::get('/admin/types', 'FngTypeController@getAll');
    Route::get('/admin/types/get/{id}', 'FngTypeController@getById');
    Route::delete('/admin/types/delete/{id}', 'FngTypeController@delete');

    //Category Routes

    Route::post('/admin/categories/create', 'FngCategoryController@create');
    Route::put('/admin/categories/update/{id}', 'FngCategoryController@update');
    Route::get('/admin/categories', 'FngCategoryController@getAll');
    Route::get('/admin/categories/father', 'FngCategoryController@getFather');
    Route::get('/admin/categories/get/{id}', 'FngCategoryController@getById');
    Route::delete('/admin/categories/delete/{id}', 'FngCategoryController@delete');

    //Product Routes


});
