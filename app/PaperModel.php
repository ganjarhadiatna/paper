<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaperModel extends Model
{
    protected $table = 'papers';

    function scopeGetID($query)
    {
        return DB::table('papers')
        ->orderBy('idpapers', 'desc')
        ->limit(1)
        ->value('idpapers');
    }
    function scopeGetIduser($query, $idpapers)
    {
        return DB::table('papers')
        ->where('papers.idpapers', $idpapers)
        ->value('papers.id');
    }
    function scopeAddPaper($query, $data)
    {
        return DB::table('papers')->insert($data);
    }
    function scopeUpdatePaper($query, $idpapers, $id, $data)
    {
        return DB::table('papers')
        ->where('papers.idpapers', $idpapers)
        ->where('papers.id', $id)
        ->update($data);
    }
    function scopeDeletePaper($query, $idpapers, $id)
    {
        return DB::table('papers')
        ->where('papers.idpapers', $idpapers)
        ->where('papers.id', $id)
        ->delete();
    }
    function scopeCheckPaper($query, $idpapers)
    {
        return DB::table('papers')
        ->where('papers.idpapers', $idpapers)
        ->value('papers.idpapers');
    }
    function scopeUpdateViewsPaper($query, $idpapers)
    {
        $no = (DB::table('papers')->where('idpapers', $idpapers)->value('views'))+1;
        return DB::table('papers')
        ->where('papers.idpapers', $idpapers)
        ->update(['papers.views' => $no]);
    }
    function scopeGetPaper($query, $idpapers)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('papers')
        ->select(
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.about',
            'users.created_at',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(image.idimage) from image where image.idpapers = papers.idpapers) as ttl_image'),
            DB::raw('(select count(watchs.idwatchs) from watchs where watchs.idpapers = papers.idpapers) as ttl_watch')
        )
        ->join('users','users.id', '=', 'papers.id')
        ->where('papers.idpapers', $idpapers)
        ->get();
    }
    function scopePagUserBookmark($query, $limit, $iduser)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('bookmark')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('image','image.idimage', '=', 'bookmark.idimage')
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('bookmark.id', $iduser)
        ->orderBy('bookmark.idbookmark', 'desc')
        ->paginate($limit);
    }
    function scopeDetailPaper($query, $limit, $id)
    {
        return DB::table('papers')
        ->select(
            'papers.idpapers',
            'papers.title',
            'papers.description',
            'papers.created',
            'papers.id',
            'papers.views',
            'users.username',
            'users.foto',
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 0) as cover1'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 1) as cover2'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 2) as cover3'),
            DB::raw('(select count(image.idimage) from image where image.idpapers = papers.idpapers) as ttl_image'),
            DB::raw('(select count(watchs.idwatchs) from watchs where watchs.idpapers = papers.idpapers) as ttl_watch')
        )
        ->join('users','users.id', '=', 'papers.id')
        ->where('papers.id', $id)
        ->orderBy('papers.title','asc')
        ->paginate($limit);
    }
    function scopeTagPaper($query, $ctr, $limit)
    {
        return DB::table('papers')
        ->select(
            'papers.idpapers',
            'papers.title',
            'papers.description',
            'papers.created',
            'papers.id',
            'papers.views',
            'users.username',
            'users.foto',
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 0) as cover1'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 1) as cover2'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 2) as cover3'),
            DB::raw('(select count(image.idimage) from image where image.idpapers = papers.idpapers) as ttl_image'),
            DB::raw('(select count(watchs.idwatchs) from watchs where watchs.idpapers = papers.idpapers) as ttl_watch')
        )
        ->join('tags','tags.idtarget', '=', 'papers.idpapers')
        ->join('users','users.id', '=', 'papers.id')
        ->where('tags.type', 'paper')
        ->where('tags.tag', 'like', '%'.$ctr.'%')
        ->orderBy('tags.idtags', 'desc')
        ->paginate($limit);
    }
}
