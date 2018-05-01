<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

use App\PaperModel;
use App\TagModel;
use App\WatchModel;
use App\BookmarkModel;
use App\DesignModel;

class PaperController extends Controller
{
    protected function mentions($tags, $idpapers)
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

    /*CRUD Papers*/
    function view($idpapers)
    {
        PaperModel::UpdateViewsPaper($idpapers);
        $id = Auth::id();
        $getPaper = PaperModel::GetPaper($idpapers);
        $paperImage = PaperModel::pagImagePaper(20, $idpapers);
        $tags = TagModel::GetTags($idpapers);
        $watchStatus = WatchModel::Check($idpapers, $id);
        return view('papers.index', [
            'title' => 'Paper',
            'path' => 'none',
            'getPaper' => $getPaper,
            'paperImage' => $paperImage,
            'tags' => $tags,
            'idpapers' => $idpapers,
            'id' => $id,
            'watchStatus' => $watchStatus
        ]);
    }
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
    function edit(Request $request)
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
    function delete(Request $request)
    {
        $iduser = Auth::id();
        $idpapers = $request['idpapers'];

        //deleting cover
        $cover = DesignModel::GetAllDesign($idpapers);
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
}
