<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BoxsModel extends Model
{
    protected $table = 'boxs';

    function scopeGetID($query)
    {
        return DB::table('boxs')
        ->orderBy('idboxs', 'desc')
        ->limit(1)
        ->value('idboxs');
    }
    function scopeGetIduser($query, $idboxs)
    {
        return DB::table('boxs')
        ->where('boxs.idboxs', $idboxs)
        ->value('boxs.id');
    }
    function scopeAddBoxs($query, $data)
    {
        return DB::table('boxs')->insert($data);
    }
    function scopeUpdateBoxs($query, $idboxs, $data)
    {
        return DB::table('boxs')->where('boxs.idboxs', $idboxs)->update($data);
    }
    function scopeDeleteBoxs($query, $idboxs, $id)
    {
        return DB::table('boxs')
        ->where('boxs.idboxs', $idboxs)
        ->where('boxs.id', $id)
        ->delete();
    }
    function scopeCheckBoxs($query, $idboxs)
    {
        return DB::table('boxs')
        ->where('boxs.idboxs', $idboxs)
        ->value('boxs.idboxs');
    }
    function scopeUpdateViewsBoxs($query, $idboxs)
    {
        $no = (DB::table('boxs')->where('idboxs', $idboxs)->value('views'))+1;
        return DB::table('boxs')
        ->where('boxs.idboxs', $idboxs)
        ->update(['boxs.views' => $no]);
    }
    function scopeGetBoxs($query, $idboxs)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('boxs')
        ->select(
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.about',
            'users.created_at',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment')
        )
        ->join('users','users.id', '=', 'boxs.id')
        ->where('boxs.idboxs', $idboxs)
        ->get();
    }
    function scopePagAllboxs($query, $limit)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('boxs.idboxs', 'desc')
        ->paginate($limit);
    }
    function scopePagRelatedboxs($query, $limit, $idboxs)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('boxs.idboxs', 'desc')
        ->paginate($limit);
    }
    function scopePagPopularboxs($query, $limit)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->paginate($limit);
    }
    /*trending belum benar karena komentar belum ada*/
    function scopePagTrendingboxs($query, $limit)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->paginate($limit);
    }
    function scopePagSearchboxs($query, $ctr, $limit)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('boxs.description','like',"%$ctr%")
        ->orWhere('users.name','like',"%$ctr%")
        ->orWhere(function ($q) use ($searchValues)
        {
            foreach ($searchValues as $value) {
                $q->orWhere('boxs.description','like',"%$value%");
            }
        })
        ->paginate($limit);
    }
    function scopePagTagboxs($query, $ctr, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('tags')
        ->select(
            'tags.idtags',
            'image.idimage',
            'image.image as cover',
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'tags.idboxs')
        ->join('image','image.idboxs', '=', 'tags.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('tags.tag', 'like', "%{$ctr}%")
        ->orderBy('tags.idtags', 'desc')
        ->paginate($limit);
    }
    function scopePagCtrboxs($query, $ctr, $limit)
    {
        if (Auth::id()) {
            $id = Auth::id();
        } else {
            $id = 0;
        }
        return DB::table('category')
        ->select(
            'category.idcategory',
            'image.idimage',
            'image.image as cover',
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idcategory', '=', 'category.idcategory')
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('category.title', $ctr)
        ->orderBy('boxs.idboxs', 'desc')
        ->paginate($limit);
    }
    function scopePagTimelinesStory($query, $limit, $profile)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('boxs.id', $id)
        ->orWhere(function ($q) use ($profile)
        {
            foreach ($profile as $value) {
                $q->orWhere('boxs.id', $value->following);
            }
        })
        ->orderBy('image.idimage', 'desc')
        ->paginate($limit);
    }
    function scopePagUserBoxs($query, $limit, $iduser)
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('boxs.id', $iduser)
        ->orderBy('boxs.idboxs', 'desc')
        ->paginate($limit);
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
            'boxs.idboxs',
            'boxs.created',
            'boxs.title',
            'boxs.description',
            'boxs.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(boxs.idboxs) from boxs where boxs.id = users.id) as ttl_boxs'),
            DB::raw('(select count(comment.idcomment) from comment where comment.idboxs = boxs.idboxs) as ttl_comment'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('image','image.idimage', '=', 'bookmark.idimage')
        ->join('boxs','boxs.idboxs', '=', 'image.idboxs')
        ->join('users','users.id', '=', 'image.id')
        ->where('bookmark.id', $iduser)
        ->orderBy('bookmark.idbookmark', 'desc')
        ->paginate($limit);
    }
    function scopeDetailBoxs($query, $limit, $id)
    {
        return DB::table('boxs')
        ->select(
            'boxs.idboxs',
            'boxs.title',
            'boxs.description',
            'boxs.created',
            'boxs.id',
            'users.username',
            'users.foto',
            DB::raw('(select image.image from image where image.idboxs = boxs.idboxs limit 1 offset 0) as cover1'),
            DB::raw('(select image.image from image where image.idboxs = boxs.idboxs limit 1 offset 1) as cover2'),
            DB::raw('(select image.image from image where image.idboxs = boxs.idboxs limit 1 offset 2) as cover3'),
            DB::raw('(select count(image.idimage) from image where image.idboxs = boxs.idboxs) as ttl_save')
        )
        ->join('users','users.id', '=', 'boxs.id')
        ->where('boxs.id', $id)
        ->orderBy('boxs.title','asc')
        ->paginate($limit);
    }
}
