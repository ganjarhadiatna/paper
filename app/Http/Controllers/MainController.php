<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\PaperModel;
use App\ProfileModel;
use App\TagModel;
use App\DesignModel;
use App\WatchModel;
use App\BookmarkModel;

class MainController extends Controller
{
    function feeds()
    {
        if (Auth::id()) {
            $id = Auth::id();
            $papers = WatchModel::GetAllWatch($id);
            $topStory = DesignModel::PagTimelinesDesign(30, $papers);
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
    function tagsDesign($ctr)
    {
        $topStory = DesignModel::PagTagDesign($ctr, 12);
        return view('tags.design', [
            'title' => $ctr,
            'path' => 'none',
            'ctr' => $ctr,
            'topStory' => $topStory
        ]);
    }
    function tagsPaper($ctr)
    {
        $topPaper = paperModel::TagPaper($ctr, 12);
        return view('tags.paper', [
            'title' => $ctr,
            'path' => 'none',
            'ctr' => $ctr,
            'topPaper' => $topPaper
        ]);
    }
    function timelines()
    {
        $id = Auth::id();
        $papers = WatchModel::GetAllWatch($id);
        $topStory = DesignModel::PagTimelinesDesign(30, $papers);
        return view('others.index', [
            'title' => 'Timelines',
            'path' => 'timelines',
            'topStory' => $topStory
        ]);
    }
    function explore()
    {
        $topStory = DesignModel::PagAllDesign(30);
        $topTags = TagModel::TopTags(5);
        return view('explore.index', [
            'title' => 'Explore',
            'path' => 'fresh',
            'topStory' => $topStory,
            'topTags' => $topTags
        ]);
    }
    function news($idtags)
    {
        $ctr = TagModel::GetTagByIdtags($idtags);
        $topStory = DesignModel::PagTagDesign($ctr, 30);
        $topTags = TagModel::TopTags(5);
        return view('explore.index', [
            'title' => 'Populars',
            'path' => 'tag-'.$idtags,
            'topStory' => $topStory,
            'topTags' => $topTags
        ]);
    }
    function popular()
    {
        $topStory = DesignModel::PagPopularDesign(30);
        $topTags = TagModel::TopTags(5);
        return view('explore.index', [
            'title' => 'Populars',
            'path' => 'populars',
            'topStory' => $topStory,
            'topTags' => $topTags
        ]);
    }
    function fresh()
    {
        $topStory = DesignModel::PagAllDesign(30);
        $topTags = TagModel::TopTags(5);
        return view('explore.index', [
            'title' => 'Fresh',
            'path' => 'fresh',
            'topStory' => $topStory,
            'topTags' => $topTags
        ]);
    }
    function trending()
    {
        $topStory = DesignModel::PagTrendingDesign(30);
        $topTags = TagModel::TopTags(5);
        return view('explore.index', [
            'title' => 'Trendings',
            'path' => 'trendings',
            'topStory' => $topStory,
            'topTags' => $topTags
        ]);
    }
    function search($ctr)
    {
        if (Auth::id()) {
            $id = Auth::id();   
        } else {
            $id = 0;
        }
        $topStory = DesignModel::PagSearchDesign($ctr, 30);
        $topTags = TagModel::SearchTags($ctr);
        return view('search.index', [
            'title' => $ctr,
            'path' => 'home-search',
            'topStory' => $topStory,
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
        $topStory = DesignModel::PagSearchDesign($ctr, 30);
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
}
