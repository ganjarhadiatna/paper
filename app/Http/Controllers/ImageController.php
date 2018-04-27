<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Storage;

use App\StoryModel;
use App\ImageModel;

class ImageController extends Controller
{
    function upload(Request $request)
    {
    	$id = Auth::id();
		$image = $request->file('image');
		$idboxs = $request['idboxs'];

		if (csrf_token()) {
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
					'idboxs' => $idboxs
				);
				$rest = ImageModel::AddImage($data);
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
					$idimage = ImageModel::GetId($id, $idboxs);
					$final = array(
						'filename' => $filename,
						'id' => $id,
						'idboxs' => $idboxs,
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
		$idimage = $request['idimage'];
		$filename = ImageModel::GetImage($idimage);
		if ($filename) {
			//delete from database
			$del = ImageModel::DeleteImage($idimage);
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
