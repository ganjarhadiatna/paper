<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImageModel extends Model
{
	protected $table = 'image';

    function scopeAddImage($query, $data)
    {
        return DB::table('image')
        ->insert($data);
    }
    function scopeDeleteImage($query, $idimage)
    {
        return DB::table('image')
        ->where('idimage', $idimage)
        ->delete();
    }
    function scopeGetImage($query, $idimage)
    {
        return DB::table('image')
        ->where('idimage', $idimage)
        ->value('image');
    }
    function scopeGetAllImage($query, $idpapers, $stt)
    {
        return DB::table('image')
        ->select(
            'idimage',
            'image',
            'id',
            'idpapers'
        )
        ->where('idpapers', $idpapers)
        ->orderBy('image.idimage',$stt)
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
    function scopeGetIdImage($query, $idpapers, $stt)
    {
        return DB::table('image')
        ->where('idpapers',$idpapers)
        ->orderBy('idimage',$stt)
        ->limit(1)
        ->value('idimage');
    }
}
