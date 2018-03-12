<?php


Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('inicio', ['as' => 'inicio', 'uses' => 'HomeController@index'])->middleware('auth');

Route::group(['middleware' => ['auth', 'admin'],'namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('inicio', ['as' => 'inicio', 'uses' => 'AdminController@index']);//solo admi
//    Route::get('inicio', ['as' => 'inicio', 'uses' => 'AdminController@index2']);
    Route::resource('usuarios', 'UsersController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
    Route::resource('fincas', 'FarmsController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
    Route::resource('animales', 'AnimalsController', ['only' => ['index', 'create', 'store']]);
    Route::resource('inspecciones', 'InspectionsController');
    Route::get('historial', ['as' => 'historial', 'uses' => 'HistoryController@index']);
});
Route::group(['middleware' => ['auth', 'inspector'],'namespace' => 'Inspector', 'prefix' => 'inspector', 'as' => 'inspector.'], function() {
    Route::get('inicio', ['as' => 'inicio', 'uses' => 'InspController@index']);//solo admi
    //Route::resource('usuarios', 'UsersController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
});

Route::group(['middleware' => ['auth', 'farmer'],'namespace' => 'Farmer', 'prefix' => 'farmer', 'as' => 'farmer.'], function() {
    Route::get('inicio', ['as' => 'inicio', 'uses' => 'FarmController@index']);//solo admi
    //Route::resource('usuarios', 'UsersController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
});

//Route::resource('inspectores', 'InspectorController', ['middleware' => ['auth' ,'inspector']]);

//Route::get('productores', ['as' => 'productores', 'uses' => 'FarmerController@index'])->middleware('farmer');

Route::get('productores/fincas/{farm}', ['as' => 'productores.fincas', 'uses' => 'FarmerController@getFarm'])->middleware('farmer');