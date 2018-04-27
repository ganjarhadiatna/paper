<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CommentModel;
use App\NotifModel;
use App\ImageModel;

class CommentController extends Controller
{
    function add(Request $req)
    {
    	if (Auth::id()) {
    		$id = Auth::id();
    	} else {
    		$id = 0;
    	}
    	$idimage = $req['idimage'];
    	$description = $req['description'];
    	$data = array(
    		'description' => $description,
    		'idimage' => $idimage,
    		'id' => $id
    	);
    	$rest = CommentModel::Add($data);
    	if ($rest) {
            //get user id
            $iduser = ImageModel::GetIduser($idimage);
            if ($id != $iduser) {
				//get papers id GetIdpaper
				$idpapers = ImageModel::GetIdpaper($idimage);
                //get idcomment
                $idcomment = CommentModel::GetIdcomment($idimage, $id);
                //add notif comment
                $notif = array(
					'idpapers' => $idpapers,
                    'idimage' => $idimage,
                    'idcomment' => $idcomment,
                    'id' => $id,
                    'iduser' => $iduser,
                    'type' => 'comment'
                );
                NotifModel::AddNotifS($notif);
            }
    		echo $idimage;
    	} else {
    		echo "failed";
    	}
    }
    function delete(Request $req)
    {
    	$idcomment = $req['idcomment'];
    	$rest = CommentModel::Remove($idcomment);
    	if ($rest) {
    		echo "success";
    	} else {
    		echo "failed";
    	}
    }
    function get($idimage, $offset, $limit)
    {
    	$rest = CommentModel::GetID($idimage, $offset, $limit);
    	echo json_encode($rest);
    }
}
