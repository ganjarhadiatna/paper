<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentModel extends Model
{
    function scopeGetIdcomment($query, $idimage, $iduser)
    {
        return DB::table('comment')
        ->where('comment.idimage', $idimage)
        ->where('comment.id', $iduser)
        ->orderBy('comment.idcomment', 'desc')
        ->limit(1)
        ->value('idcomment');
    }
    function scopeAdd($scope, $data)
    {
    	return DB::table('comment')
    	->insert($data);
    }
    function scopeRemove($scope, $idcomment)
    {
    	return DB::table('comment')
    	->where('comment.idcomment', $idcomment)
    	->delete();
    }
    function scopeGetID($scope, $idimage, $offset, $limit)
    {
    	return DB::table('comment')
    	->select(
    		'comment.idcomment',
    		'comment.description',
    		'comment.created',
    		'comment.id',
    		'users.name',
    		'users.foto'
    	)
    	->where('comment.idimage',$idimage)
    	->join('users','users.id','=','comment.id')
    	->orderBy('comment.idcomment','desc')
    	->offset($offset)
    	->limit($limit)
    	->get();
    }
}
