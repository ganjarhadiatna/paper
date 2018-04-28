<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\FollowModel;
use App\NotifModel;

class FollowController extends Controller
{    
    function create(Request $request)
    {
    	$id = Auth::id();
    	$iduser = $request['iduser'];

    	//check following
    	$ck = FollowModel::Check($iduser, $id);
    	if (is_int($ck)) {
    		echo "failed";
    	} else {
    		$data = array(
	    		'following' => $iduser,
	    		'followers' => $id
	    	);
	    	//save to follow
	    	$rest = FollowModel::Add($data);
	    	if ($rest) {
                //add notif
                $notif = array(
                    'id' => $id,
                    'iduser' => $iduser
                );
                NotifModel::AddNotifF($notif);
                //
	    		echo 'success';	
	    	} else {
	    		echo 'failed';
	    	}
    	}
    }
    function readFollowing($iduser)
    {
        if (Auth::id()) {
            $id = Auth::id();   
        } else {
            $id = 0;
        }
        $profile = FollowModel::GetUserFollowing($iduser, $id);
        $ttl_following = FollowModel::GetTotalFollowing($iduser);
        return view('profile.follow.following', [
            'title' => 'Following',
            'path' => 'profile',
            'profile' => $profile,
            'ttl_following' => $ttl_following
        ]);
    }
    function readFollowers($iduser)
    {
        if (Auth::id()) {
            $id = Auth::id();   
        } else {
            $id = 0;
        }
        $profile = FollowModel::GetUserFollowers($iduser, $id);
        $ttl_followers = FollowModel::GetTotalFollowers($iduser);
        return view(
            'profile.follow.followers', [
            'title' => 'Followers',
            'path' => 'profile',
            'profile' => $profile,
            'ttl_followers' => $ttl_followers
        ]);
    }
    function delete(Request $request)
    {
    	$id = Auth::id();
    	$iduser = $request['iduser'];

    	//save to follow
    	$rest = FollowModel::Remove($iduser, $id);
    	if ($rest) {
    		echo 'success';	
    	} else {
    		echo 'failed';
    	}
    }

    function check(Request $request)
    {
    	$id = Auth::id();
    	$iduser = $request['iduser'];

    	//save to follow
    	$rest = FollowModel::Check($iduser, $id);
    	if ($rest) {
    		echo 'success';	
    	} else {
    		echo 'failed';
    	}
    }
}
