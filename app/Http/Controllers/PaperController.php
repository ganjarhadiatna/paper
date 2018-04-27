<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

use App\PaperModel;
use App\TagModel;
use App\FollowModel;
use App\BookmarkModel;
use App\ImageModel;

class PaperController extends Controller
{
    function paper($id)
    {
        $check = PaperModel::CheckPaper($id);
        if (is_int($check)) {
            $idimage = ImageModel::GetIdImage($id, 'asc');
            if (is_int($idimage)) {
                PaperModel::UpdateViewsPaper($id);
                $iduserMe = Auth::id();
                $iduser = PaperModel::GetIduser($id);
                
                $getStory = PaperModel::GetPaper($id);
                $getImage = ImageModel::GetImage($idimage);
                $getAllImage = ImageModel::GetAllImage($id, 'desc');

                $newStory = PaperModel::PagRelatedPaper(20, $id);

                $tags = TagModel::GetTags($id);
                $statusFolow = FollowModel::Check($iduser, $iduserMe);
                $check = BookmarkModel::Check($idimage, $iduserMe);
                return view('papers.index', [
                    'title' => 'Paper',
                    'path' => 'none',
                    'getStory' => $getStory,
                    'getImage' => $getImage,
                    'getAllImage' => $getAllImage,
                    'newStory' => $newStory,
                    'tags' => $tags,
                    'check' => $check,
                    'statusFolow' => $statusFolow,
                    'idpapers' => $id,
                    'idimage' => $idimage
                ]);
            } else {
                return redirect('/paper/'.$id.'/designs');
            }
        } else {
            return view('papers.empty', [
                'title' => 'Paper Not Finded',
                'path' => 'none',
            ]);
        }
    }
    function paperImage($id, $idimage)
    {
        $check = PaperModel::CheckPaper($id);
        if (is_int($check)) {
            PaperModel::UpdateViewsPaper($id);
            $iduserMe = Auth::id();
            $iduser = PaperModel::GetIduser($id);
            
            $getStory = PaperModel::GetPaper($id);
            $getImage = ImageModel::GetImage($idimage);
            $getAllImage = ImageModel::GetAllImage($id,'desc');
            
            $newStory = PaperModel::PagRelatedPaper(20, $id);
            
            $tags = TagModel::GetTags($id);
            $statusFolow = FollowModel::Check($iduser, $iduserMe);
            $check = BookmarkModel::Check($idimage, $iduserMe);
            return view('papers.index', [
                'title' => 'Paper',
                'path' => 'none',
                'getStory' => $getStory,
                'getImage' => $getImage,
                'getAllImage' => $getAllImage,
                'newStory' => $newStory,
                'tags' => $tags,
                'check' => $check,
                'statusFolow' => $statusFolow,
                'idpapers' => $id,
                'idimage' => $idimage
            ]);
        } else {
            return view('papers.empty', [
                'title' => 'Paper Not Finded',
                'path' => 'none',
            ]);
        }
    }
    function paperEdit($idpapers)
    {
        $getStory = PaperModel::GetPaper($idpapers);
        $restTags = TagModel::GetTags($idpapers);
        $temp = [];
        foreach ($restTags as $tag) {
            array_push($temp, $tag->tag);
        }
        $tags = implode(", ", $temp);
        return view('compose.edit-paper', [
            'title' => 'Edit Paper',
            'path' => 'none',
            'getStory' => $getStory,
            'tags' => $tags
        ]);
    }
    function mentions($tags, $idpapers)
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
                    'idpapers' => $idpapers
                ]);
                TagModel::AddTags($data);
            }
        }
    }
    function addLoves(Request $request)
    {
        $idstory = $request['idstory'];
        $ttl = $request['ttl-loves'];
        PaperModel::UpdateLoves($idstory, $ttl);
        $rest = PaperModel::GetLoves($idstory);
        echo $rest;
    }

    /*Setting Papers*/
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

    	$rest = PaperModel::AddPaper($data);
    	if ($rest) {
    		$dt = PaperModel::GetID();
            $this->mentions($request['tags'], $dt);
    		echo $dt;
    	} else {
    		echo 0;
        }
    }
    function editPaper(Request $request)
    {
        $idpapers = $request['idpapers'];
        $title = $request['title'];
        $content = $request['content'];
        $tags = $request['tags'];

        $data = array(
            'title' => $title,
            'description' => $content
        );

        $rest = PaperModel::UpdatePaper($idpapers, $data);
        if ($rest) {
            //remove tags
            TagModel::DeleteTags($idpapers);

            //editting tags
            $this->mentions($request['tags'], $idpapers);
            echo $idpapers;
        } else {
            echo "failed";
        }
    }
    function deletePaper(Request $request)
    {
        $iduser = Auth::id();
        $idpapers = $request['idpapers'];

        //deleting cover
        $cover = ImageModel::GetAllImage($idpapers);
        foreach ($cover as $dt) {
            unlink(public_path('story/covers/'.$dt->image));
			unlink(public_path('story/thumbnails/'.$dt->image));
        }

        //deleting story
        $rest = PaperModel::DeletePaper($idpapers, $iduser);
        if ($rest) {
            echo "success";
        } else {
            echo "failed";
        }
    }
}
