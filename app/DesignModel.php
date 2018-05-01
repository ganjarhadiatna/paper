<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DesignModel extends Model
{
	protected $table = 'image';

    function scopeAddDesign($query, $data)
    {
        return DB::table('image')
        ->insert($data);
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
    function scopeGetAllDesign($query, $idpapers, $stt)
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
}
