<?php

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

Route::group(['namespace' => 'auth\registration\memberrequest', 'middleware' => 'api', 'prefix' => 'registration/memberrequest'], function () {
    Route::post('/create', 'MemberRequestController')->name('memberRequest');
    Route::post('/approve', 'MemberRequestController@approve')->name('approveMemberRequest');
    Route::post('/refuse', 'MemberRequestController@refuse')->name('refuseMemberRequest');
    Route::get('/all', 'MemberRequestController@getAll')->name('getAllMemberRequests');
});
