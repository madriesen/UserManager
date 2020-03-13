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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace'=>'auth\registration', 'middleware' => 'api'], function(){
    Route::post('/memberRequest', 'MemberRequestController')->name('memberRequest');
    Route::post('/approveMemberRequest', 'MemberRequestController@approve')->name('approveMemberRequest');
    Route::post('/denyMemberRequest', 'MemberRequestController@deny')->name('denyMemberRequest');
});