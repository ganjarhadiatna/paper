<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BookmarkModel;
use App\DesignModel;
use App\NotifModel;

class BookmarkController extends Controller
{
	function create(Request $request)
    {
    	$id = Auth::id();
    	$idimage = $request['idimage'];
    	$ch = BookmarkModel::Check($idimage, $id);
    	if (is_int($ch)) {
    		$rest = BookmarkModel::Remove($idimage, $id);
		    if ($rest) {
		    	echo "unbookmark";	
		    } else {
		    	echo "failedremove";
		    }
    	} else {
    		$data = array(
				'idimage' => $idimage,
				'id' => $id
			);
	    	$rest = BookmarkModel::Add($data);
	    	if ($rest) {
	    		//get user id
	    		$iduser = DesignModel::GetIduser($idimage);
	    		if ($id != $iduser) {
					//get papers id GetIdpaper
					$idpapers = DesignModel::GetIdpaper($idimage);
	    			//get bookmark id
		    		$idbookmark = BookmarkModel::GetIduser($iduser);
		    		//add notif bookmark
		    		$notif = array(
						'idpapers' => $idpapers,
		    			'idimage' => $idimage,
		    			'idbookmark' => $idbookmark,
		    			'id' => $id,
		    			'iduser' => $iduser,
		    			'type' => 'bookmark'
		    		);
		    		NotifModel::AddNotifS($notif);
	    		}
	    		echo "bookmark";	
	    	} else {
	    		echo "failedadd";
	    	}
    	}
    }
    function delete(Request $request)
    {
    	$idbookmark = $request['idbookmark'];
	    $rest = BookmarkModel::Remove($idbookmark);
	    if ($rest) {
	    	echo 1;
	    } else {
	    	echo 0;
	    }
    }
}
