<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:Api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Auth\Registration\MemberRequest', 'middleware' => 'Api', 'prefix' => 'registration/memberrequest'], function () {
    Route::post('/create', 'MemberRequestController')->name('memberRequest');
    Route::post('/approve', ['uses' => 'MemberRequestController@response', 'response' => 'approve'])->name('approveMemberRequest');
    Route::post('/refuse', ['uses' => 'MemberRequestController@response', 'response' => 'refuse'])->name('refuseMemberRequest');
    Route::get('/all', 'MemberRequestController@getAll')->name('getAllMemberRequests');
});

Route::group(['namespace' => 'Auth\Registration\Invite', 'middleware' => 'Api', 'prefix' => 'registration/invite'], function () {
    Route::post('/create', 'InviteController')->name('invite');
    Route::post('/accept', ['uses' => 'InviteController@response', 'response' => 'accept'])->name('acceptInvite');
    Route::post('/decline', ['uses' => 'InviteController@response', 'response' => 'decline'])->name('declineInvite');
    Route::get('/all', 'InviteController@getAll')->name('getAllInvites');
});

Route::group(['namespace' => 'Auth\Account', 'middleware' => 'Api', 'prefix' => 'account'], function () {
    Route::post('/create', 'AccountController')->name('account');
    Route::get('/all', 'AccountController@getAll')->name('getAllAccounts');
});

Route::group(['namespace' => 'Auth\Profile', 'middleware' => 'Api', 'prefix' => 'profile'], function () {
    Route::post('/create', 'ProfileController')->name('profile');
    Route::post('/update', 'ProfileController@update')->name('updateProfile');
});
