<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => ['auth:api', 'cors']], function () {
    Route::post('/upload', 'UploadController@upload');
    Route::get('/uploads', 'UploadController@myUploads');
});
Route::get('file/{file}', 'FileController@viewFile')->name('file');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'HomeController@index');
    Route::get('/myuploads', 'FileController@uploads')->name('my-uploads');
    Route::get('/favourites', 'FileController@favourites');

    Route::group(['prefix' => 'file/{file}/'], function () {
        Route::post('favourite', 'FileController@favourite')->name('favourite');
        Route::post('delete', 'FileController@delete')->name('delete');
        Route::post('description', 'FileController@addFileDescription')->name('addFileDescription');
        Route::post('view-edit-description', 'FileController@viewEditDescription')->name('viewEditFileDescription');
        Route::post('update-description', 'FileController@editDescription')->name('updateFileDescription');
        Route::post('remove-description', 'FileController@removeDescription')->name('removeFileDescription');
    });

});
