<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;

use App\PaperModel;
use App\DesignModel;
use App\ProfileModel;
use App\TagModel;

class ProfileController extends Controller
{
    function profile()
	{
		$id = Auth::id();
        $profile = ProfileModel::UserData($id);
        $userPapers = PaperModel::DetailPaper(20, $id);
        return view('profile.papers', [
            'title' => 'User Profile',
            'path' => 'profile',
            'nav' => 'papers',
            'profile' => $profile,
            'userPapers' => $userPapers
        ]);
	}
    function designs($id)
	{
		$iduser = Auth::id();
		if ($iduser == $id) {
			$pathProfile = 'profile';
		} else {
			$pathProfile = 'none';
		}
        $profile = ProfileModel::UserData($id);
        $userStory = DesignModel::PagUserDesign(20, $id);
        return view('profile.designs', [
            'title' => 'User Profile',
            'path' => $pathProfile,
            'nav' => 'design',
            'profile' => $profile,
            'userStory' => $userStory
        ]);
	}
	function Paper($id)
	{
		$iduser = Auth::id();
		if ($iduser == $id) {
			$pathProfile = 'profile';
		} else {
			$pathProfile = 'none';
		}
        $profile = ProfileModel::UserData($id);
        $userPapers = PaperModel::DetailPaper(20, $id);
        return view('profile.papers', [
            'title' => 'User Profile',
            'path' => $pathProfile,
            'nav' => 'papers',
            'profile' => $profile,
            'userPapers' => $userPapers
        ]);
	}
	function saved($id)
	{
		$iduser = Auth::id();
		if ($iduser == $id) {
			$pathProfile = 'profile';
		} else {
			$pathProfile = 'none';
		}
        $profile = ProfileModel::UserData($id);
        $userStory = DesignModel::PagUserBookmark(20, $id);
        return view('profile.saved', [
            'title' => 'User Profile',
            'path' => $pathProfile,
            'nav' => 'saved',
            'profile' => $profile,
            'userStory' => $userStory
        ]);
	}
	function profileSetting()
    {
        $id = Auth::id();
        $profile = ProfileModel::UserData($id);
        return view('profile.setting.setting', [
            'title' => 'Profile Setting',
            'path' => 'profile',
            'profile' => $profile
        ]);
    }
    function profileSettingProfile()
    {
        $id = Auth::id();
        $profile = ProfileModel::UserData($id);
        return view('profile.setting.edit', [
            'title' => 'Edit Profile',
            'path' => 'profile',
            'profile' => $profile
        ]);
    }
    function profileSettingPassword()
    {
        $id = Auth::id();
        $profile = ProfileModel::UserData($id);
        return view('profile.setting.password', [
            'title' => 'Change Password',
            'path' => 'profile',
            'profile' => $profile
        ]);
    }
    function savePassword(Request $request)
    {
        $id = Auth::id();
        $old_password = $request['old_password'];
        $new_password = $request['new_password'];
        $renew_password = $request['renew_password'];
        $data_password = ProfileModel::GetPass($id);
        if (Hash::check($old_password, $data_password)) {
            if ($new_password == $renew_password) {
                $request->user()->fill([
                    'password' => Hash::make($new_password)
                ])->save();
                echo "done";
            } else {
                echo "not_seem";
            }
        } else {
            echo "false";
        }
    }
    function saveProfile(Request $request)
    {
    	$id = Auth::id();
    	$foto = $request['foto'];
    	$name = $request['name'];
        $username = $request['username'];
    	$email = $request['email'];
    	$about = $request['about'];
    	$website = $request['website'];

    	if ($request->hasFile('foto')) {
    		//setting foto profile
	    	$this->validate($request, [
	    		'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
	    	]);

    		$image = $request->file('foto');

    		$chrc = array('[',']','@',' ','+','-','#','*','<','>','_','(',')',';',',','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
		    $filename = $id.time().str_replace($chrc, '', $image->getClientOriginalName());

		    //create thumbnail
		    $destination = public_path('profile/thumbnails/'.$filename);
		    $img = Image::make($image->getRealPath());
		    $img->resize(200, 200, function ($constraint) {
		    	$constraint->aspectRatio();
		    })->save($destination);

		    //create image real
		    $destination = public_path('profile/photos/');
		    $image->move($destination, $filename);	

		    //set array data
		    $data = array(
		    	'name' => $name,
                'username' => $username,
		    	'email' => $email,
		    	'about' => $about,
		    	'foto' => $filename,
		    	'website' => $website
		    );
    	} else {
    		//set array data
		    $data = array(
		    	'name' => $name,
                'username' => $username,
		    	'email' => $email,
		    	'about' => $about,
		    	'website' => $website
		    );
    	}
	    $rest = ProfileModel::EditProfile($id, $data);
	    if ($rest) {
	    	echo "success";	
	    } else {
	    	echo "failed";
	    }
    }
}
