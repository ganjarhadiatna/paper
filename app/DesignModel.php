<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DesignModel extends Model
{
	protected $table = 'image';

    function scopeGetAllPict($query)
    {
        return DB::table('image')
        ->select(
            'idimage',
            'image',
            'description',
            'views',
            'created'
        )
        ->get();
    }

    function scopeAddDesign($query, $data)
    {
        return DB::table('image')
        ->insert($data);
    }
    function scopeEditDesign($query, $idimage, $id, $data)
    {
        return DB::table('image')
        ->where('idimage', $idimage)
        ->where('id', $id)
        ->update($data);
    }
    function scopeDeleteDesign($query, $idimage, $id)
    {
        return DB::table('image')
        ->where('idimage', $idimage)
        ->where('id', $id)
        ->delete();
    }
    function scopeGetDesign($query, $idimage)
    {
        return DB::table('image')
        ->where('idimage', $idimage)
        ->value('image');
    }
    function scopeGetDetailDesign($query, $idimage)
    {
        return DB::table('image')
        ->select(
            'image',
            'description',
            'views',
            'width',
            'height',
            'created'
        )
        ->where('idimage', $idimage)
        ->get();
    }
    function scopeUpdateViewsImage($query, $idimage)
    {
        $no = (DB::table('image')->where('idimage', $idimage)->value('views'))+1;
        return DB::table('image')
        ->where('image.idimage', $idimage)
        ->update(['image.views' => $no]);
    }
    function scopeGetAllDesign($query, $idpapers, $stt, $limit=8)
    {
        return DB::table('image')
        ->select(
            'idimage',
            'image',
            'id',
            'idpapers'
        )
        ->where('idpapers', $idpapers)
        ->orderBy('image.idimage', $stt)
        ->limit($limit)
        ->get();
    }
    function scopeGetId($query, $id, $idpapers)
    {
        return DB::table('image')
        ->where('id',$id)
        ->where('idpapers',$idpapers)
        ->limit(1)
        ->orderBy('idimage','desc')
        ->value('idimage');
    }
    function scopeGetIdImage($query, $id, $idpapers, $idimage)
    {
        return DB::table('image')
        ->where('idimage',$idimage)
        ->where('id',$id)
        ->where('idpapers',$idpapers)
        ->value('idimage');
    }
    function scopeGetIdDesign($query, $idpapers, $stt)
    {
        return DB::table('image')
        ->where('idpapers',$idpapers)
        ->orderBy('idimage',$stt)
        ->limit(1)
        ->value('idimage');
    }
    function scopeGetIduser($query, $idimage)
    {
        return DB::table('image')
        ->where('image.idimage', $idimage)
        ->value('image.id');
    }
    function scopeGetIdpaper($query, $idimage)
    {
        return DB::table('image')
        ->where('image.idimage', $idimage)
        ->value('image.idpapers');
    }
    function scopePagAllDesign($query, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('image.idimage', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagRelatedDesign($query, $limit, $idpapers)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('papers.idpapers', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagPopularDesign($query, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_bookmark'),
            DB::raw('(select (count(comment.idcomment) + ttl_bookmark + image.views)/3 from comment where comment.idimage = image.idimage) as ttl_comment'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagTrendingDesign($query, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(comment.idcomment) from comment where comment.idimage = image.idimage) as ttl_comment'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagSearchDesign($query, $ctr, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        $searchValues = preg_split('/\s+/', $ctr, -1, PREG_SPLIT_NO_EMPTY);
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orWhere('papers.title','like',"%$ctr%")
        ->orWhere('papers.description','like',"%$ctr%")
        ->orWhere('image.description','like',"%$ctr%")
        ->orWhere('users.name','like',"%$ctr%")
        ->orWhere(function ($q) use ($searchValues)
        {
            foreach ($searchValues as $value) {
                $q->orWhere('papers.title','like',"%$value%");
                $q->orWhere('papers.description','like',"%$value%");
                $q->orWhere('image.description','like',"%$value%");
            }
        })
        ->orderBy('image.idimage', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagTagDesign($query, $ctr, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        $searchValues = preg_split('/\s+/', $ctr, -1, PREG_SPLIT_NO_EMPTY);
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->leftJoin('tags','tags.idtarget', '=', 'image.idimage')
        ->where('tags.tag', 'like', '%'.$ctr.'%')
        ->orWhere('papers.title','like',"%$ctr%")
        ->orWhere('papers.description','like',"%$ctr%")
        ->orWhere('image.description','like',"%$ctr%")
        ->orWhere('users.name','like',"%$ctr%")
        ->orWhere(function ($q) use ($searchValues)
        {
            foreach ($searchValues as $value) {
                $q->orWhere('image.description','like',"%$value%");
                $q->orWhere('papers.title','like',"%$value%");
                $q->orWhere('papers.description','like',"%$value%");
            }
        })
        ->orderBy('image.idimage', 'desc')
        ->groupBy('image.idimage')
        ->simplePaginate($limit);
    }
    function scopePagTimelinesDesign($query, $limit, $paper)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.id', $id)
        ->orWhere(function ($q) use ($paper)
        {
            foreach ($paper as $value) {
                $q->orWhere('papers.idpapers', $value->idpapers);
            }
        })
        ->orderBy('image.idimage', 'desc')
        ->simplePaginate($limit);
    }
    function scopePagUserDesign($query, $limit, $iduser)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.id', $iduser)
        ->orderBy('image.idimage', 'desc')
        ->simplePaginate($limit);
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
            'image.width',
            'image.height',
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
        ->simplePaginate($limit);
    }
    function scopePagImagePaper($query, $limit, $idpapers)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('image')
        ->select(
            'image.idimage',
            'image.image as cover',
            'image.description',
            'image.width',
            'image.height',
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
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.idpapers', $idpapers)
        ->orderBy('image.idimage', 'desc')
        ->simplePaginate($limit);
    }
}
