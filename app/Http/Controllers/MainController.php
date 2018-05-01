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
            $topStory = PaperModel::PagTimelinesStory(20, $papers);
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
    function tags($ctr)
    {
        $topStory = PaperModel::PagTagPaper($ctr, 12);
        return view('others.index', [
            'title' => $ctr,
            'path' => 'none',
            'topStory' => $topStory
        ]);
    }
    function timelines()
    {
        $id = Auth::id();
        $papers = WatchModel::GetAllWatch($id);
        $topStory = PaperModel::PagTimelinesPaper(20, $papers);
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
}
