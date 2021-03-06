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
    function scopeCheckMyPaper($query, $id, $idpapers)
    {
        return DB::table('papers')
        ->where('papers.id', $id)
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
    function scopeUserSmallPaper($query, $id)
    {
        return DB::table('papers')
        ->select(
            'papers.idpapers',
            'papers.title',
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 0) as cover')
        )
        ->where('papers.id', $id)
        ->orderBy('papers.title','asc')
        ->get();
    }
    function scopeUserSelectedPaper($query, $id, $idpapers)
    {
        return DB::table('papers')
        ->select(
            'papers.idpapers',
            'papers.title',
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 0) as cover')
        )
        ->where('papers.id', $id)
        ->where('papers.idpapers', $idpapers)
        ->orderBy('papers.title','asc')
        ->get();
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
        ->simplePaginate($limit);
    }
    function scopeTagPaper($query, $ctr, $limit)
    {
        $searchValues = preg_split('/\s+/', $ctr, -1, PREG_SPLIT_NO_EMPTY);
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
        ->leftJoin('tags','tags.idtarget', '=', 'papers.idpapers')
        ->where('tags.tag', 'like', '%'.$ctr.'%')
        ->orWhere('papers.title','like',"%$ctr%")
        ->orWhere('papers.description','like',"%$ctr%")
        ->orWhere('users.name','like',"%$ctr%")
        ->orWhere(function ($q) use ($searchValues)
        {
            foreach ($searchValues as $value) {
                $q->orWhere('papers.title','like',"%$value%");
                $q->orWhere('papers.description','like',"%$value%");
            }
        })
        ->orderBy('papers.idpapers', 'desc')
        ->groupBy('papers.idpapers')
        ->simplePaginate($limit);
    }
}
