<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentModel extends Model
{
    function scopeGetIdcomment($query, $idboxs, $iduser)
    {
        return DB::table('comment')
        ->where('comment.idboxs', $idboxs)
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
    function scopeGetID($scope, $idboxs, $offset, $limit)
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
    	->where('comment.idboxs',$idboxs)
    	->join('users','users.id','=','comment.id')
    	->orderBy('comment.idcomment','desc')
    	->offset($offset)
    	->limit($limit)
    	->get();
    }
}
