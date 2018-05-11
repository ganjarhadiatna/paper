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
use App\TagModel;

class DesignController extends Controller
{
	protected function mentions($tags, $idimage)
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
                    'idtarget' => $idimage,
                    'type' => 'design'
                ]);
                TagModel::AddTags($data);
            }
        }
	}
	
	function view($idpapers, $idimage)
    {
		$iduser = PaperModel::GetIduser($idpapers);
		$check = DesignModel::GetIdImage($iduser, $idpapers, $idimage);
        if (is_int($check)) {
            DesignModel::UpdateViewsImage($idimage);
			$iduserMe = Auth::id();
            
            $getPaper = PaperModel::GetPaper($idpapers);
            $getImage = DesignModel::GetDetailDesign($idimage);
            $getAllImage = DesignModel::GetAllDesign($idpapers,'desc');
            
			$newStory = DesignModel::PagRelatedDesign(20, $idpapers);
			
			$tags = TagModel::GetTags($idimage, 'design');

            $check = BookmarkModel::Check($idimage, $iduserMe);
            return view('designs.index', [
                'title' => 'Design',
                'path' => 'none',
                'getPaper' => $getPaper,
                'getImage' => $getImage,
                'getAllImage' => $getAllImage,
                'newStory' => $newStory,
				'check' => $check,
				'tags' => $tags,
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
			$getImage = DesignModel::GetDetailDesign($idimage);
			$restTags = TagModel::GetTags($idimage,'design');
			$papers = PaperModel::UserSmallPaper($id);
			$selectedPaper = PaperModel::UserSelectedPaper($id, $idpapers);
			$image = DesignModel::GetDesign($idimage);
            $temp = [];
            foreach ($restTags as $tag) {
                array_push($temp, $tag->tag);
            }
            $tags = implode(", ", $temp);
			return view('designs.edit', [
                'title' => 'Edit Design',
				'path' => 'none',
				'idpapers' => $idpapers,
				'idimage' => $idimage,
				'getImage' => $getImage,
				'image' => $image,
				'tags' => $tags,
				'papers' => $papers,
				'selectedPaper' => $selectedPaper
			]);
		} else {
			return view('designs.empty', [
                'title' => 'Empty',
                'path' => 'none'
            ]);
		}
	}
	function publishDesign(Request $request)
	{
		$id = Auth::id();
		$image = $request->file('image');
		$idpapers = $request['idpapers'];
		$content = $request['content'];
		$tags = $request['tags'];
		$check = PaperModel::CheckMyPaper($id, $idpapers);
        if (is_int($check)) {
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
					'idpapers' => $idpapers,
					'description' => $content,
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

					//save tags
					$this->mentions($request['tags'], $idimage);

					//success
					echo $idimage;
				} else {
					echo 'no-save';
				}
			} else {
				echo 'no-image';
			}
		} else {
			echo 'no-paper';
		}
	}
    function publish(Request $request)
    {
		$id = Auth::id();
		$idpapers = $request['idpapers'];
		$iduser = PaperModel::GetIduser($idpapers);
		if ($iduser == $id) {
			if ($request->hasFile('image')) {

				$image = $request->file('image');
				$final = array();

				for ($i=0; $i < count($image); $i++) {
					//validate
					/*$this->validate($request, [
						'image' => 'required',
						'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1000000',
					]);*/

					//rename file
					$chrc = array('[',']','@',' ','+','-','#','*','<','>','_','(',')',';',',','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
					$filename = $id.time().str_replace($chrc, '', $image[$i]->getClientOriginalName());

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
						$img = Image::make($image[$i]->getRealPath());
						$img->resize(400, 400, function ($constraint) {
							$constraint->aspectRatio();
						})->save($destination); 

						//saving image real to server
						$destination = public_path('story/covers/');
						$image[$i]->move($destination, $filename);

						//getting last idimage
						$idimage = DesignModel::GetId($id, $idpapers);

						array_push($final, [
							'filename' => $filename,
							'id' => $id,
							'idpapers' => $idpapers,
							'idimage' => $idimage,
							'description' => '',
							'created' => ''
						]);
					} else {
						echo "failed-saving";
					}
				}
				echo json_encode($final);
			} else {
				echo 'no file';
			}
		} else {
			echo "no-token";
		}
	}
	function edit(Request $request)
	{
		$id = Auth::id();
		$idpaper = $request['idpaper'];
		$idimage = $request['idimage'];
		$description = $request['content'];
		$data = array(
			'idimage' => $idimage,
			'description' => $description,
			'idpapers' => $idpaper
		);
		$rest = DesignModel::EditDesign($idimage, $id, $data);
		if (is_int($rest)) {
			//remove tags
			TagModel::DeleteTags($idimage, 'design');

			//editting tags
			$this->mentions($request['tags'], $idimage);
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	function delete(Request $request)
	{
		$id = Auth::id();
		$idimage = $request['idimage'];
		$filename = DesignModel::GetDesign($idimage);
		if ($filename) {
			//remove tags
			TagModel::DeleteTags($idimage, 'design');

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
