<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

use App\BoxsModel;
use App\TagModel;
use App\FollowModel;
use App\BookmarkModel;
use App\ImageModel;

class BoxsController extends Controller
{
    function Boxs($id)
    {
        $check = BoxsModel::CheckBoxs($id);
        if (is_int($check)) {
            $idimage = ImageModel::GetIdImage($id, 'asc');
            if (is_int($idimage)) {
                BoxsModel::UpdateViewsBoxs($id);
                $iduserMe = Auth::id();
                $iduser = BoxsModel::GetIduser($id);
                
                $getStory = BoxsModel::GetBoxs($id);
                $getImage = ImageModel::GetImage($idimage);
                $getAllImage = ImageModel::GetAllImage($id, 'desc');

                $newStory = BoxsModel::PagRelatedBoxs(20, $id);

                $tags = TagModel::GetTags($id);
                $statusFolow = FollowModel::Check($iduser, $iduserMe);
                $check = BookmarkModel::Check($idimage, $iduserMe);
                return view('boxs.index', [
                    'title' => 'Box',
                    'path' => 'none',
                    'getStory' => $getStory,
                    'getImage' => $getImage,
                    'getAllImage' => $getAllImage,
                    'newStory' => $newStory,
                    'tags' => $tags,
                    'check' => $check,
                    'statusFolow' => $statusFolow,
                    'idboxs' => $id,
                    'idimage' => $idimage
                ]);
            } else {
                return redirect('/box/'.$id.'/designs');
            }
        } else {
            return view('boxs.empty', [
                'title' => 'Box Not Finded',
                'path' => 'none',
            ]);
        }
    }
    function boxsImage($id, $idimage)
    {
        $check = BoxsModel::CheckBoxs($id);
        if (is_int($check)) {
            BoxsModel::UpdateViewsBoxs($id);
            $iduserMe = Auth::id();
            $iduser = BoxsModel::GetIduser($id);
            
            $getStory = BoxsModel::GetBoxs($id);
            $getImage = ImageModel::GetImage($idimage);
            $getAllImage = ImageModel::GetAllImage($id,'desc');
            
            $newStory = BoxsModel::PagRelatedBoxs(20, $id);
            
            $tags = TagModel::GetTags($id);
            $statusFolow = FollowModel::Check($iduser, $iduserMe);
            $check = BookmarkModel::Check($idimage, $iduserMe);
            return view('boxs.index', [
                'title' => 'Design',
                'path' => 'none',
                'getStory' => $getStory,
                'getImage' => $getImage,
                'getAllImage' => $getAllImage,
                'newStory' => $newStory,
                'tags' => $tags,
                'check' => $check,
                'statusFolow' => $statusFolow,
                'idboxs' => $id,
                'idimage' => $idimage
            ]);
        } else {
            return view('boxs.empty', [
                'title' => 'Box Not Finded',
                'path' => 'none',
            ]);
        }
    }
    function boxsEdit($idboxs)
    {
        $getStory = BoxsModel::GetBoxs($idboxs);
        $restTags = TagModel::GetTags($idboxs);
        $temp = [];
        foreach ($restTags as $tag) {
            array_push($temp, $tag->tag);
        }
        $tags = implode(", ", $temp);
        return view('compose.edit-box', [
            'title' => 'Edit Box',
            'path' => 'none',
            'getStory' => $getStory,
            'tags' => $tags
        ]);
    }
    function mentions($tags, $idboxs)
    {
        $replace = array('[',']','@','+','-','*','<','>','-','(',')',';','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
        $str1 = str_replace($replace, '', $tags);
        $str2 = str_replace(array(', ', ' , ', ' ,'), ',', $str1);
        $tag = explode(',', $str2);
        $count_tag = count($tag);

        for ($i = 0; $i < $count_tag; $i++) {
            if ($tag[$i] != '') {
                $data = array([
                    'tag' => $tag[$i],
                    'link' => '',
                    'idboxs' => $idboxs
                ]);
                TagModel::AddTags($data);
            }
        }
    }
    function addLoves(Request $request)
    {
        $idstory = $request['idstory'];
        $ttl = $request['ttl-loves'];
        BoxsModel::UpdateLoves($idstory, $ttl);
        $rest = BoxsModel::GetLoves($idstory);
        echo $rest;
    }

    /*Setting boxs*/
    function publish(Request $request)
    {
    	$id = Auth::id();
    	$title = $request['title'];
    	$content = $request['content'];
    	$adult = 0;
    	$commenting = 0;

    	$data = array(
            'title' => $title,
    		'description' => $content,
    		'id' => $id
    	);

    	$rest = BoxsModel::AddBoxs($data);
    	if ($rest) {
    		$dt = BoxsModel::GetID();
            $this->mentions($request['tags'], $dt);
    		echo $dt;
    	} else {
    		echo 0;
        }
    }
    function editBoxs(Request $request)
    {
        $idboxs = $request['idboxs'];
        $title = $request['title'];
        $content = $request['content'];
        $tags = $request['tags'];

        $data = array(
            'title' => $title,
            'description' => $content
        );

        $rest = BoxsModel::UpdateBoxs($idboxs, $data);
        if ($rest) {
            //remove tags
            TagModel::DeleteTags($idboxs);

            //editting tags
            $this->mentions($request['tags'], $idboxs);
            echo $idboxs;
        } else {
            echo "failed";
        }
    }
    function deleteBoxs(Request $request)
    {
        $iduser = Auth::id();
        $idboxs = $request['idboxs'];

        //deleting cover
        $cover = ImageModel::GetAllImage($idboxs);
        foreach ($cover as $dt) {
            unlink(public_path('story/covers/'.$dt->image));
			unlink(public_path('story/thumbnails/'.$dt->image));
        }

        //deleting story
        $rest = BoxsModel::DeleteBoxs($idboxs, $iduser);
        if ($rest) {
            echo "success";
        } else {
            echo "failed";
        }
    }
}
