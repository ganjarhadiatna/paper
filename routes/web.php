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
Route::get('/', 'MainController@feeds');
Route::get('/home', 'MainController@feeds');
Route::get('/tags/{ctr}', 'MainController@tagsDesign');
Route::get('/tags/design/{ctr}', 'MainController@tagsDesign');
Route::get('/tags/paper/{ctr}', 'MainController@tagsPaper');
Route::get('/popular', 'MainController@popular');
Route::get('/fresh', 'MainController@fresh');
Route::get('/trending', 'MainController@trending');
Route::get('/search/{ctr}', 'MainController@search');
Route::get('/search', 'MainController@searchNormal');
Route::get('/s/{id}', 'StoryController@story')->where(['id' => '[0-9]+']);

/*user*/
Route::get('/user/{iduser}', 'ProfileController@paper')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/designs', 'ProfileController@designs')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/papers', 'ProfileController@paper')->where(['iduser' => '[0-9]+']);
Route::get('/user/{iduser}/saved', 'ProfileController@saved')->where(['iduser' => '[0-9]+']);

/*loves*/
Route::post('/loves/add', 'StoryController@addLoves');

/*comment*/
Route::get('/get/comment/{idimage}/{offset}/{limit}', 'CommentController@read')->where(['idimage' => '[0-9]+']);

/*paper*/
Route::get('/paper/{idpapers}', 'PaperController@view')->where(['idpapers' => '[0-9]+']);
Route::get('/paper/{idpapers}/design/{idimage}', 'DesignController@view')
->where(['idpapers' => '[0-9]+','idimage' => '[0-9]+']);

Auth::routes();
Route::middleware('auth')->group(function() {
    /*user*/
    Route::get('/user/{iduser}/following', 'FollowController@readFollowing')->where(['iduser' => '[0-9]+']);
    Route::get('/user/{iduser}/followers', 'FollowController@readFollowers')->where(['iduser' => '[0-9]+']);
    Route::get('/user/{iduser}/paper', 'ProfileController@paper')->where(['iduser' => '[0-9]+']);

	/*profile*/
	Route::get('/me', 'ProfileController@profile');
    Route::get('/me/setting', 'ProfileController@profileSetting');
    Route::get('/me/setting/profile', 'ProfileController@profileSettingProfile');
    Route::get('/me/setting/password', 'ProfileController@profileSettingPassword');
    
    Route::get('/timelines', 'MainController@timelines');
    
    Route::post('/save/profile', 'ProfileController@saveProfile');
    Route::post('/save/password', 'ProfileController@savePassword');

    /*compose*/
    Route::get('/compose', 'ComposeController@composeDesign');
    Route::get('/compose/design', 'ComposeController@composeDesign');
    Route::get('/compose/paper', 'ComposeController@composePaper');
    Route::get('/compose/paper/{idpapers}/designs', 'ComposeController@composePaperDesign');
    
    Route::post('/paper/image/upload', 'DesignController@publish');
    Route::post('/paper/image/delete', 'DesignController@delete');
    Route::post('/paper/publish', 'PaperController@publish');

    Route::get('/paper/{idstory}/edit', 'PaperController@paperEdit');
    Route::get('/paper/{idstory}/designs', 'ComposeController@composePaperDesign');

    Route::post('/paper/edit', 'PaperController@edit');
    Route::post('/paper/delete', 'PaperController@delete');

    Route::get('/paper/{idpapers}/design/{idimage}/edit', 'DesignController@viewEdit')
    ->where(['idpapers' => '[0-9]+','idimage' => '[0-9]+']);
    Route::post('/design/edit', 'DesignController@edit');
    Route::post('/design/delete', 'DesignController@delete');

    /*watch*/
    Route::post('/watch/add', 'WatchController@create');
    Route::post('/watch/remove', 'WatchController@delete');

    /*bookmark*/
    Route::post('/add/bookmark', 'BookmarkController@create');
    Route::post('/remove/bookmark', 'BookmarkController@delete');

    /*comment*/
    Route::post('/add/comment', 'CommentController@create');
    Route::post('/delete/comment', 'CommentController@delete');

    /*notifications*/
    Route::post('/notif/story', 'NotifController@notifStory');
    Route::post('/notif/following', 'NotifController@notifFollowing');
    Route::get('/notif/cek', 'NotifController@notifCek');
    Route::get('/notif/cek/story', 'NotifController@notifCekStory');
    Route::get('/notif/cek/following', 'NotifController@notifCekFollowing');
});