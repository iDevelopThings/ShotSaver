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
Route::get('t', function () {

    foreach (\App\Models\FileUploads::get() as $item) {
        $file     = new \Illuminate\Http\File(public_path('uploads/' . $item->file));
        $response = \Illuminate\Support\Facades\Storage::disk('spaces')
            ->putFile(
                '',
                $file,
                'public'
            );

        $item->file = $response;
        $item->save();

        unset($file);
        unset($response);
    }

});
Route::get('file/{file}', 'FileController@viewFile')->name('file');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index');
    Route::get('/myuploads', 'FileController@uploads');
    Route::group(['prefix' => 'file/{file}/'], function () {
        Route::post('description', 'FileController@addFileDescription')->name('addFileDescription');
        Route::post('view-edit-description', 'FileController@viewEditDescription')->name('viewEditFileDescription');
        Route::post('update-description', 'FileController@editDescription')->name('updateFileDescription');
        Route::post('remove-description', 'FileController@removeDescription')->name('removeFileDescription');
    });
});
