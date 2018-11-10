<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\WatchModel;
use App\NotifModel;

class WatchController extends Controller
{
    function create(Request $request)
    {
    	$id = Auth::id();
        $idpapers = $request['idpapers'];
        $iduser = $request['iduser'];

    	//check watched
    	$ck = WatchModel::Check($idpapers, $id);
    	if (is_int($ck)) {
    		echo "failed";
    	} else {
    		$data = array(
	    		'idpapers' => $idpapers,
	    		'id' => $id
	    	);
	    	//save to follow
	    	$rest = WatchModel::Add($data);
	    	if ($rest) {
                //add notif
                $notif = array(
                    'idpapers' => $idpapers,
                    'id' => $id,
                    'iduser' => $iduser,
                    'type' => 'paper'
                );
                NotifModel::AddNotifS($notif);
                //
	    		echo 'success';
	    	} else {
	    		echo 'failed';
	    	}
    	}
    }
    function delete(Request $request)
    {
    	$id = Auth::id();
    	$idpapers = $request['idpapers'];

    	//save to follow
    	$rest = WatchModel::Remove($idpapers, $id);
    	if ($rest) {
    		echo 'success';	
    	} else {
    		echo 'failed';
    	}
    }

    function check(Request $request)
    {
    	$id = Auth::id();
    	$idpapers = $request['idpapers'];

    	//save to follow
    	$rest = WatchModel::Check($idpapers, $id);
    	if ($rest) {
    		echo 'success';	
    	} else {
    		echo 'failed';
    	}
    }
}
