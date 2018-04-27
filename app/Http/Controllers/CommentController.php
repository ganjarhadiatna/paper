<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CommentModel;
use App\NotifModel;
use App\BoxsModel;

class CommentController extends Controller
{
    function add(Request $req)
    {
    	if (Auth::id()) {
    		$id = Auth::id();
    	} else {
    		$id = 0;
    	}
    	$idboxs = $req['idboxs'];
    	$description = $req['description'];
    	$data = array(
    		'description' => $description,
    		'idboxs' => $idboxs,
    		'id' => $id
    	);
    	$rest = CommentModel::Add($data);
    	if ($rest) {
            //get user id
            $iduser = BoxsModel::GetIduser($idboxs);
            if ($id != $iduser) {
                //get idcomment
                $idcomment = CommentModel::GetIdcomment($idboxs, $id);
                //add notif comment
                $notif = array(
                    'idboxs' => $idboxs,
                    'idcomment' => $idcomment,
                    'id' => $id,
                    'iduser' => $iduser,
                    'title' => 'Commented on your Story',
                    'type' => 'comment'
                );
                NotifModel::AddNotifS($notif);
            }
    		echo $idboxs;
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
    function get($idboxs, $offset, $limit)
    {
    	$rest = CommentModel::GetID($idboxs, $offset, $limit);
    	echo json_encode($rest);
    }
}
