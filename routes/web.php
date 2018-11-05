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


Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('uploads/thumbnails/{path}', 'FileController@getThumbnail');
Route::get('uploads/{path}', 'FileController@getFile');


Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'MainController@app');
});

Route::group(['middleware' => ['auth'], 'prefix' => '/api'], function () {
    Route::put('projects/{project}/cards/{card}/column', 'CardController@column');
    Route::get('notifications', 'NotificationController@get');
    Route::get('notifications/read', 'NotificationController@read');
    Route::get('notifications/clear', 'NotificationController@clear');

    Route::resource('projects', 'ProjectController');
    Route::resource('projects.cards', 'CardController');
    Route::post('cards/{card}/favorite', 'CardController@favorite');
    Route::post('projects/{project}/favorite', 'ProjectController@favorite');

    Route::resource('projects.cards.comments', 'CardCommentController');
    Route::resource('projects.cards.tasks', 'CardTaskController');

    Route::resource('logs', 'LogController');
    Route::get('logs/current', 'LogController@current');
    Route::get('logs', 'LogController@index');
    Route::get('logs/toggle/{card}', 'LogController@toggle');

    Route::resource('events', 'EventController');

    Route::resource('topics', 'TopicController');
    Route::post('topics/{topic}/favorite', 'TopicController@favorite');
    Route::resource('topics.messages', 'TopicMessageController');
    Route::resource('topics.files', 'TopicFilesController');
    Route::resource('topics.events', 'TopicEventController');
    Route::resource('files', 'FileController');

    Route::get('users/current', 'UserController@currentUser');
    Route::resource('users', 'UserController');
    Route::resource('favorites', 'FavoriteController', ['only' => ['index']]);
    Route::post('users/{user}/upload', 'UserController@upload');

    Route::post('upload', 'FileController@upload');

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('translation', 'TranslationController@index');
});

Route::get('calendar/{hash}/calendar.ics', 'EventController@ical');
