<?php

// Главная страница
Route::get('/', [
	'uses' => 'Docs\DocumentController@getIndex'
]);

// Страница документа
Route::get('/document/{id}', [
	'uses' => 'Docs\DocumentController@getDocument',
	'as' => 'document',
]);

// Запросы к api основного сервиса
Route::controller('documents/api', 'Docs\ApiController', []);


/*
|--------------------------------------------------------------------------
| Return view file (for Angular)
|--------------------------------------------------------------------------
|
| Выдаем шаблон для angular.
|
*/
Route::get('ng/{path}', function($path){
	return View::make( 'ng/' . $path );
})->where([
	'path' => '.*'
]);