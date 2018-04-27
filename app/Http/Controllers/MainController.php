<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\PaperModel;
use App\ProfileModel;
use App\TagModel;
use App\ImageModel;
use App\FollowModel;
use App\BookmarkModel;

class MainController extends Controller
{
    function index()
    {
        if (Auth::id()) {
            $id = Auth::id();
            $profile = FollowModel::GetAllFollowing($id);
            $topStory = PaperModel::PagTimelinesStory(20, $profile, $id);
            return view('home.index', [
                'title' => 'Official Site',
                'path' => 'home',
                'topStory' => $topStory
            ]);
        } else {
            return view('home.home', [
                'title' => 'Official Site',
                'path' => 'home'
            ]);
        }
    }
    function collections()
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        $topStory = PaperModel::PagAllStory(20);
        $topTags = TagModel::TopTags();
        $allTags = TagModel::AllTags();
        $topUsers = ProfileModel::TopUsers($id, 7);
        return view('collections.index', [
            'title' => 'Collections',
            'path' => 'collections',
            'topStory' => $topStory,
            'topTags' => $topTags,
            'allTags' => $allTags,
            'topUsers' => $topUsers
        ]);
    }
    function collectionsId($ctr)
    {
        return view('others.index', ['title' => 'Collections', 'path' => 'collections']);
    }
    function tagsId($ctr)
    {
        $topStory = PaperModel::PagTagPaper($ctr, 12);
        return view('others.index', [
            'title' => $ctr,
            'path' => 'none',
            'topStory' => $topStory
        ]);
    }
    function ctrId($ctr)
    {
        $topStory = PaperModel::PagCtrPaper($ctr, 12);
        return view('others.index', [
            'title' => 'Category '.$ctr,
            'path' => 'none',
            'topStory' => $topStory
        ]);
    }
    function timelines()
    {
        $id = Auth::id();
        $profile = FollowModel::GetAllFollowing($id);
        $topStory = PaperModel::PagTimelinesPaper(20, $profile);
        return view('others.index', [
            'title' => 'Timelines',
            'path' => 'timelines',
            'topStory' => $topStory
        ]);
    }
    function popular()
    {
        $topStory = PaperModel::PagPopularPaper(20);
        return view('others.index', [
            'title' => 'Popular',
            'path' => 'popular',
            'topStory' => $topStory
        ]);
    }
    function composeImage($idpapers)
    {
        $image = ImageModel::GetAllImage($idpapers,'asc');
        return view('compose.image', [
            'title' => 'Add Designs',
            'path' => 'compose',
            'idpapers' => $idpapers,
            'image' => $image
        ]);
    }
    function composePaper()
    {
        return view('compose.paper', [
            'title' => 'Add Paper',
            'path' => 'compose'
        ]);
    }
    function fresh()
    {
        $topStory = PaperModel::PagAllPaper(20);
        return view('others.index', [
            'title' => 'Fresh',
            'path' => 'fresh',
            'topStory' => $topStory
        ]);
    }
    function trending()
    {
        $topStory = PaperModel::PagTrendingPaper(20);
        return view('others.index', [
            'title' => 'Trending',
            'path' => 'trending',
            'topStory' => $topStory
        ]);
    }
    function search($ctr)
    {
        if (Auth::id()) {
            $id = Auth::id();   
        } else {
            $id = 0;
        }
        $topStory = PaperModel::PagSearchPaper($ctr, 20);
        $topUsers = ProfileModel::SearchUsers($ctr, $id);
        $topTags = TagModel::SearchTags($ctr);
        return view('search.index', [
            'title' => $ctr,
            'path' => 'home-search',
            'topStory' => $topStory,
            'topUsers' => $topUsers,
            'topTags' => $topTags
        ]);
    }
    function searchNormal()
    {
        if (Auth::id()) {
            $id = Auth::id();   
        } else {
            $id = 0;
        }
        $ctr = $_GET['q'];
        $topStory = PaperModel::PagSearchPaper($ctr, 20);
        $topUsers = ProfileModel::SearchUsers($ctr, $id);
        $topTags = TagModel::SearchTags($ctr);
        return view('search.index', [
            'title' => $ctr,
            'path' => 'home-search',
            'topStory' => $topStory,
            'topUsers' => $topUsers,
            'topTags' => $topTags
        ]);
    }
    function login()
    {
        return view('sign.in', ['title' => 'Login', 'path' => 'none']);
    }
    function signup()
    {
        return view('sign.up', ['title' => 'Signup', 'path' => 'none']);
    }
}
