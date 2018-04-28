<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NotifModel extends Model
{
    function scopeCekNotifStory($query, $iduser)
    {
        return DB::table('notif_s')
        ->where('notif_s.iduser', $iduser)
        ->where('notif_s.status','=','unread')
        ->count();
    }
    function scopeCekNotifFollowing($query, $iduser)
    {
        return DB::table('notif_f')
        ->where('notif_f.iduser', $iduser)
        ->where('notif_f.status','=','unread')
        ->count();
    }

    function scopeUpdateNotifS($query, $iduser)
    {
        return DB::table('notif_s')
        ->where('iduser', $iduser)
        ->where('status', '=' ,'unread')
        ->update(array('status' => 'read'));
    }

    function scopeUpdateNotifF($query, $iduser)
    {
        return DB::table('notif_f')
        ->where('iduser', $iduser)
        ->where('status', '=' ,'unread')
        ->update(array('status' => 'read'));
    }

    function scopeAddNotifF($query, $data)
    {
        return DB::table('notif_f')
        ->insert($data);
    }
    function scopeAddNotifS($query, $data)
    {
        return DB::table('notif_s')
        ->insert($data);
    }

    function scopeGetNotifF($query, $id, $limit, $offset)
    {
        return DB::table('notif_f')
        ->select(
            'notif_f.idnotif_f',
            'notif_f.id',
            'notif_f.iduser',
            'notif_f.created',
            'users.name',
            'users.username',
            'users.foto',
            'users.about'
        )
        ->where('notif_f.iduser', $id)
        ->join('users','users.id','=','notif_f.id')
        ->orderBy('notif_f.idnotif_f', 'desc')
        ->limit($limit)
        ->offset($offset)
        ->get();
    }
    function scopeGetNotifS($query, $id, $limit, $offset)
    {
        return DB::table('notif_s')
        ->select(
            'notif_s.idnotif_s',
            'notif_s.status',
            'notif_s.id',
            'notif_s.iduser',
            'notif_s.idimage',
            'notif_s.created',
            'notif_s.type',
            'users.name',
            'users.username',
            'users.foto',
            'users.about',
            'image.image',
            'image.idpapers',
            'papers.title',
            'comment.description'
        )
        ->where('notif_s.iduser', $id)
        ->join('users','users.id','=','notif_s.id')
        ->join('image','image.idimage','=','notif_s.idimage')
        ->join('papers','papers.idpapers','=','notif_s.idpapers')
        ->leftJoin('comment','comment.idcomment','=','notif_s.idcomment')
        ->orderBy('notif_s.idnotif_s', 'desc')
        ->limit($limit)
        ->offset($offset)
        ->get();
    }
}
