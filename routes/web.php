<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/channel', [App\Http\Controllers\HomeController::class, 'channel'])->name('channel');
Route::post('/search_channel', [App\Http\Controllers\HomeController::class, 'search_channel'])->name('search_channel');
Route::post('/add_channel', [App\Http\Controllers\HomeController::class, 'add_channel'])->name('add_channel');
Route::get('/video_list/{id}', [App\Http\Controllers\HomeController::class, 'video_list'])->name('video_list');
Route::get('/video_detail/{id}', [App\Http\Controllers\HomeController::class, 'video_detail'])->name('video_detail');
