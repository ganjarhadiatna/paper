<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WatchModel extends Model
{
    function scopeAdd($query, $data)
    {
    	return DB::table('watchs')
    	->insert($data);
    }
    function scopeRemove($query, $idpapers, $id)
    {
    	return DB::table('watchs')
    	->where('idpapers', $idpapers)
    	->where('id', $id)
    	->delete();
    }
    function scopeCheck($query, $idpapers, $id)
    {
    	return DB::table('watchs')
    	->where('idpapers', $idpapers)
    	->where('id', $id)
    	->value('idwatchs');
    }
    function scopeGetAllWatch($query, $iduser)
    {
        return DB::table('watchs')
        ->select('idpapers')
        ->where('id', $iduser)
        ->get();
    }
}
