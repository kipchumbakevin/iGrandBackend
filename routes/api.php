<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
//sendcode
Route::post('sendcode', 'CodesController@sendCode');
Route::post('checkdetails', 'CodesController@checkDetails');

//forgot password
Route::post('check', 'ForgotPassword@checkIfExist');
Route::post('forgotpassword', 'ForgotPassword@forgotPassword');

//change password
Route::post('changepass', 'ChangePassPhone@changePassword');
//change phone
Route::post('checkphone', 'ChangePassPhone@checkIfExist');
Route::post('changephone', 'ChangePassPhone@changePhone');
//upload video
Route::post('uploadVid', 'VideosController@doupload');
//fetch video
Route::get('getvid', 'VideosController@fetchAll');
//upload magazine
Route::post('uploadMag', 'MagazineController@doupload');
//fetch magazines
Route::get('getmag', 'MagazineController@fetchAll');
//upload podcast
Route::post('uploadPod', 'PodcastController@doupload');
//fetch podcast
Route::get('getpod', 'PodcastController@fetchAll');
//upload client details;
Route::post('uploadPCli', 'ClientDocsController@doupload');
//fetch client details
Route::get('getdocs', 'fetchController@fetchAll');
