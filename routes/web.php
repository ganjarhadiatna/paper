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

Route::get('/', 'MainController@index');
Route::get('/home', 'MainController@index');
Route::get('/tags/{ctr}', 'MainController@tagsId');
Route::get('/category/{ctr}', 'MainController@ctrId');
Route::get('/popular', 'MainController@popular');
Route::get('/fresh', 'MainController@fresh');
Route::get('/trending', 'MainController@trending');
Route::get('/search/{ctr}', 'MainController@search');
Route::get('/search', 'MainController@searchNormal');
Route::get('/s/{id}', 'StoryController@story')->where(['id' => '[0-9]+']);

/*user*/
Route::get('/user/{iduser}', 'ProfileController@boxs')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/designs', 'ProfileController@designs')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/boxs', 'ProfileController@boxs')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/saved', 'ProfileController@saved')->where(['iduser' => '[0-9]+']);

/*loves*/
Route::post('/loves/add', 'StoryController@addLoves');

/*comment*/
Route::get('/get/comment/{idstory}/{offset}/{limit}', 'CommentController@get')->where(['idstory' => '[0-9]+']);

/*boxs*/
Route::get('/box/{idboxs}', 'BoxsController@boxs')->where(['idboxs' => '[0-9]+']);
Route::get('/box/{idboxs}/design/{idimage}', 'BoxsController@boxsImage')
->where(['idboxs' => '[0-9]+','idimage' => '[0-9]+']);

Auth::routes();
Route::middleware('auth')->group(function() {
    /*user*/
    Route::get('/user/{iduser}/following', 'FollowController@following')->where(['iduser' => '[0-9]+']);
    Route::get('/user/{iduser}/followers', 'FollowController@followers')->where(['iduser' => '[0-9]+']);
    Route::get('/user/{iduser}/boxs', 'ProfileController@boxs')->where(['iduser' => '[0-9]+']);

	/*profile*/
	Route::get('/me', 'ProfileController@profile');
    Route::get('/me/setting', 'ProfileController@profileSetting');
    Route::get('/me/setting/profile', 'ProfileController@profileSettingProfile');
    Route::get('/me/setting/password', 'ProfileController@profileSettingPassword');
    
    Route::get('/timelines', 'MainController@timelines');
    
    Route::post('/save/profile', 'ProfileController@saveProfile');
    Route::post('/save/password', 'ProfileController@savePassword');

    /*compose*/
    Route::get('/compose', 'MainController@composeBox');
    Route::get('/compose/box', 'MainController@composeBox');
    Route::get('/compose/box/{idbox}/designs', 'MainController@composeImage');
    
    Route::post('/box/image/upload', 'ImageController@upload');
    Route::post('/box/image/delete', 'ImageController@delete');
    Route::post('/box/publish', 'BoxsController@publish');

    Route::get('/box/{idstory}/edit', 'BoxsController@boxsEdit');
    Route::get('/box/{idstory}/designs', 'MainController@composeImage');

    Route::post('/box/edit', 'BoxsController@editBoxs');
    Route::post('/box/delete', 'BoxsController@deleteBoxs');

    /*Follow*/
    Route::post('/follow/add', 'FollowController@add');
    Route::post('/follow/remove', 'FollowController@remove');

    /*bookmark*/
    Route::post('/add/bookmark', 'BookmarkController@add');
    Route::post('/remove/bookmark', 'BookmarkController@remove');

    /*comment*/
    Route::post('/add/comment', 'CommentController@add');
    Route::post('/delete/comment', 'CommentController@delete');

    /*notifications*/
    Route::post('/notif/story', 'NotifController@notifStory');
    Route::post('/notif/following', 'NotifController@notifFollowing');
    Route::get('/notif/cek', 'NotifController@notifCek');
    Route::get('/notif/cek/story', 'NotifController@notifCekStory');
    Route::get('/notif/cek/following', 'NotifController@notifCekFollowing');
});