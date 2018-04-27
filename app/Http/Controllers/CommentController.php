<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CommentModel;
use App\NotifModel;
use App\PaperModel;

class CommentController extends Controller
{
    function add(Request $req)
    {
    	if (Auth::id()) {
    		$id = Auth::id();
    	} else {
    		$id = 0;
    	}
    	$idpapers = $req['idpapers'];
    	$description = $req['description'];
    	$data = array(
    		'description' => $description,
    		'idpapers' => $idpapers,
    		'id' => $id
    	);
    	$rest = CommentModel::Add($data);
    	if ($rest) {
            //get user id
            $iduser = PaperModel::GetIduser($idpapers);
            if ($id != $iduser) {
                //get idcomment
                $idcomment = CommentModel::GetIdcomment($idpapers, $id);
                //add notif comment
                $notif = array(
                    'idpapers' => $idpapers,
                    'idcomment' => $idcomment,
                    'id' => $id,
                    'iduser' => $iduser,
                    'title' => 'Commented on your Paper',
                    'type' => 'comment'
                );
                NotifModel::AddNotifS($notif);
            }
    		echo $idpapers;
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
    function get($idpapers, $offset, $limit)
    {
    	$rest = CommentModel::GetID($idpapers, $offset, $limit);
    	echo json_encode($rest);
    }
}
