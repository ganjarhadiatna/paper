<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Storage;

use App\StoryModel;
use App\DesignModel;
use App\PaperModel;
use App\BookmarkModel;

class DesignController extends Controller
{
	function view($idpapers, $idimage)
    {
		$iduser = PaperModel::GetIduser($idpapers);
		$check = DesignModel::GetIdImage($iduser, $idpapers, $idimage);
        if (is_int($check)) {
            DesignModel::UpdateViewsImage($idimage);
			$iduserMe = Auth::id();
            
            $getStory = PaperModel::GetPaper($idpapers);
            $getImage = DesignModel::GetDesign($idimage);
            $getAllImage = DesignModel::GetAllDesign($idpapers,'desc');
            
            $newStory = PaperModel::PagRelatedPaper(20, $idpapers);

            $check = BookmarkModel::Check($idimage, $iduserMe);
            return view('designs.index', [
                'title' => 'Design',
                'path' => 'none',
                'getStory' => $getStory,
                'getImage' => $getImage,
                'getAllImage' => $getAllImage,
                'newStory' => $newStory,
                'check' => $check,
                'idpapers' => $idpapers,
                'idimage' => $idimage
            ]);
        } else {
            return view('designs.empty', [
                'title' => 'Empty',
                'path' => 'none',
            ]);
        }
	}
	function viewEdit($idpapers, $idimage)
	{
		//check design
		$id = Auth::id();
		$check = DesignModel::GetIdImage($id, $idpapers, $idimage);
        if (is_int($check)) {
			return view('designs.edit', [
                'title' => 'Edit Design',
				'path' => 'none',
				'idimage' => $idimage
			]);
		} else {
			return view('main.denied', [
                'title' => 'Denied',
                'path' => 'none'
            ]);
		}
	}
    function publish(Request $request)
    {
    	$id = Auth::id();
		$image = $request->file('image');
		$idpapers = $request['idpapers'];

		$iduser = PaperModel::GetIduser($idpapers);
        if ($iduser == $id) {
			if ($image) {
				//validate
				$this->validate($request, [
					'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1000000',
				]);

				//rename file
				$chrc = array('[',']','@',' ','+','-','#','*','<','>','_','(',')',';',',','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
				$filename = $id.time().str_replace($chrc, '', $image->getClientOriginalName());

				$data = array(
					'image' => $filename,
					'id' => $id,
					'idpapers' => $idpapers
				);
				$rest = DesignModel::AddDesign($data);
				if ($rest) {
					//save image to server
					//creating thumbnail and save to server
					$destination = public_path('story/thumbnails/'.$filename);
					$img = Image::make($image->getRealPath());
					$img->resize(400, 400, function ($constraint) {
						$constraint->aspectRatio();
					})->save($destination); 

					//saving image real to server
					$destination = public_path('story/covers/');
					$image->move($destination, $filename);

					//getting last idimage
					$idimage = DesignModel::GetId($id, $idpapers);
					$final = array(
						'filename' => $filename,
						'id' => $id,
						'idpapers' => $idpapers,
						'idimage' => $idimage
					);
					echo json_encode($final);
				} else {
					echo "failed-saving";
				}
			} else {
				echo "no-file";
			}
		} else {
			echo "no-token";
		}
	}
	function delete(Request $request)
	{
		$id = Auth::id();
		$idimage = $request['idimage'];
		$filename = DesignModel::GetDesign($idimage);
		if ($filename) {
			//delete from database
			$del = DesignModel::DeleteDesign($idimage, $id);
			if ($del) {
				//delete from server
				unlink(public_path('story/covers/'.$filename));
				unlink(public_path('story/thumbnails/'.$filename));

				echo 1;
			} else {
				echo 99;
			}
		} else {
			echo 22;
		}
	}
}
