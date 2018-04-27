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
    function scopeUpdatePaper($query, $idpapers, $data)
    {
        return DB::table('papers')->where('papers.idpapers', $idpapers)->update($data);
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
            DB::raw('(select count(image.idimage) from image where image.idpapers = papers.idpapers) as ttl_image')
        )
        ->join('users','users.id', '=', 'papers.id')
        ->where('papers.idpapers', $idpapers)
        ->get();
    }
    function scopePagAllPaper($query, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('papers.idpapers', 'desc')
        ->paginate($limit);
    }
    function scopePagRelatedPaper($query, $limit, $idpapers)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('papers.idpapers', 'desc')
        ->paginate($limit);
    }
    function scopePagPopularPaper($query, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->paginate($limit);
    }
    /*trending belum benar karena komentar belum ada*/
    function scopePagTrendingPaper($query, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->orderBy('ttl_comment', 'desc')
        ->paginate($limit);
    }
    function scopePagSearchPaper($query, $ctr, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.description','like',"%$ctr%")
        ->orWhere('users.name','like',"%$ctr%")
        ->orWhere(function ($q) use ($searchValues)
        {
            foreach ($searchValues as $value) {
                $q->orWhere('papers.description','like',"%$value%");
            }
        })
        ->paginate($limit);
    }
    function scopePagTagPaper($query, $ctr, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'tags.idpapers')
        ->join('image','image.idpapers', '=', 'tags.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('tags.tag', 'like', "%{$ctr}%")
        ->orderBy('tags.idtags', 'desc')
        ->paginate($limit);
    }
    function scopePagCtrPaper($query, $ctr, $limit)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idcategory', '=', 'category.idcategory')
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('category.title', $ctr)
        ->orderBy('papers.idpapers', 'desc')
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.id', $id)
        ->orWhere(function ($q) use ($profile)
        {
            foreach ($profile as $value) {
                $q->orWhere('papers.id', $value->following);
            }
        })
        ->orderBy('image.idimage', 'desc')
        ->paginate($limit);
    }
    function scopePagUserPaper($query, $limit, $iduser)
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.id', $iduser)
        ->orderBy('papers.idpapers', 'desc')
        ->paginate($limit);
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
            DB::raw('(select count(bookmark.idbookmark) from bookmark where bookmark.idimage = image.idimage) as ttl_save'),
            DB::raw('(select bookmark.idbookmark from bookmark where bookmark.idimage = image.idimage and bookmark.id = '.$id.' limit 1) as is_save')
        )
        ->join('papers','papers.idpapers', '=', 'image.idpapers')
        ->join('users','users.id', '=', 'image.id')
        ->where('papers.idpapers', $idpapers)
        ->orderBy('image.idimage', 'desc')
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
            'papers.idpapers',
            'papers.created',
            'papers.title',
            'papers.description',
            'papers.views',
            'users.id',
            'users.name',
            'users.username',
            'users.visitor',
            'users.foto',
            DB::raw('(select count(papers.idpapers) from papers where papers.id = users.id) as ttl_papers'),
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
            'users.username',
            'users.foto',
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 0) as cover1'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 1) as cover2'),
            DB::raw('(select image.image from image where image.idpapers = papers.idpapers limit 1 offset 2) as cover3'),
            DB::raw('(select count(image.idimage) from image where image.idpapers = papers.idpapers) as ttl_save')
        )
        ->join('users','users.id', '=', 'papers.id')
        ->where('papers.id', $id)
        ->orderBy('papers.title','asc')
        ->paginate($limit);
    }
}
