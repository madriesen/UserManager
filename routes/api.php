<?php

use App\Mail\InviteMail;
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
    Route::post('/create', 'MemberRequestController')->name('member_request');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/approve', ['uses' => 'MemberRequestController@response', 'response' => 'approve'])->name('approve_member_request');
        Route::post('/refuse', ['uses' => 'MemberRequestController@response', 'response' => 'refuse'])->name('refuse_member_request');
        Route::get('/all', 'MemberRequestController@getAll')->name('get_all_member_requests');
    });
});

//Route::group(['namespace' => 'Auth\Registration\Invite', 'middleware' => 'Api', 'prefix' => 'registration/invite'], function () {
//    Route::group(['middleware' => 'auth:sanctum'], function () {
//        Route::post('/create', 'InviteController')->name('invite');
//        Route::post('/accept', ['uses' => 'InviteController@response', 'response' => 'accept'])->name('acceptInvite');
//        Route::post('/decline', ['uses' => 'InviteController@response', 'response' => 'decline'])->name('declineInvite');
//        Route::get('/all', 'InviteController@getAll')->name('getAllInvites');
//    });
//});

Route::group(['namespace' => 'Auth\Account', 'middleware' => 'Api', 'prefix' => 'account'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/create', 'AccountController')->name('account');
        Route::get('/all', 'AccountController@getAll')->name('getAllAccounts');
    });
});

Route::group(['namespace' => 'Auth\Profile', 'middleware' => 'Api', 'prefix' => 'profile'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/create', 'ProfileController')->name('profile');
        Route::post('/update', 'ProfileController@update')->name('updateProfile');
    });
});

Route::group(['namespace' => 'Auth', 'middleware' => 'Api', 'prefix' => 'authentication'], function () {
    Route::post('/login', 'AuthenticationController')->name('login');
    Route::middleware(['auth:sanctum'])->get('/checkLogin', function (Request $request) {
    })->name('checkLogin');
});

Route::group(['namespace' => 'Auth\Account', 'middleware' => 'Api', 'prefix' => 'accounttype'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/create', 'AccountTypesController')->name('accountType');
        Route::post('/update', 'AccountTypesController@update')->name('updateAccountType');
    });
});


Route::get('/email', function () {
//    Mail::to('test@testmail.be')->send(new InviteMail('test_uuid'));
    return new InviteMail('test_uuid');
});

