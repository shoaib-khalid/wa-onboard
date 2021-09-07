<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\FailedController;
// use App\Http\Controllers\CheckUsernameController;
use Illuminate\Http\Request;

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

Route::get('/',[IndexController::class,'loadView']);
Route::post('/',[IndexController::class,'setWebhook']);
Route::get('/failed',[FailedController::class,'loadView']);

/**
 * @hideFromAPIDocumentation
 */
Route::fallback(function () {
    //Send to 404 or whatever here.
    return abort(404);
});