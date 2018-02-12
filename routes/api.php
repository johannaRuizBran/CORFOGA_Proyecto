<?php

Route::get('login', 'Auth\LoginController@remoteLogin');
Route::get('fincas/get/{region}', 'ApiController@getFarmsByRegion');
Route::get('animales/get/{farm}', 'ApiController@getAnimalsByFarm');
Route::get('alimentacion/get', 'ApiController@getFeedingMethods');
Route::post('inspecciones/create', 'ApiController@createInspection');
