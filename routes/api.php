<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Список постов
Route::get('/post/popular/{postsCount?}', 'PostController@popular');

//Добавление поста
Route::post('/post', 'PostController@add');

//Добавление отзыва к посту
Route::post('/post/{post}/review', 'ReviewController@add');

//Список апйпи
Route::get('/ip/authors', 'UserController@listGroupByIp');


