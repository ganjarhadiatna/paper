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
	function view($id, $idimage)
    {
        $check = PaperModel::CheckPaper($id);
        if (is_int($check)) {
            PaperModel::UpdateViewsPaper($id);
            $iduserMe = Auth::id();
            $iduser = PaperModel::GetIduser($id);
            
            $getStory = PaperModel::GetPaper($id);
            $getImage = DesignModel::GetDesign($idimage);
            $getAllImage = DesignModel::GetAllDesign($id,'desc');
            
            $newStory = PaperModel::PagRelatedPaper(20, $id);

            $check = BookmarkModel::Check($idimage, $iduserMe);
            return view('designs.index', [
                'title' => 'Paper',
                'path' => 'none',
                'getStory' => $getStory,
                'getImage' => $getImage,
                'getAllImage' => $getAllImage,
                'newStory' => $newStory,
                'check' => $check,
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
