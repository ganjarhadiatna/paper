<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\DesignModel;
use App\PaperModel;

class ComposeController extends Controller
{
    function composeDesign($idpapers)
    {
        $iduser = PaperModel::GetIduser($idpapers);
        if ($iduser == Auth::id()) {
            $image = DesignModel::GetAllDesign($idpapers,'asc');
            return view('compose.image', [
                'title' => 'Add Designs',
                'path' => 'compose',
                'idpapers' => $idpapers,
                'image' => $image
            ]);
        } else {
            return view('main.denied', [
                'title' => 'Denied',
                'path' => 'none'
            ]);
        }
    }
    function composePaper()
    {
        return view('compose.paper', [
            'title' => 'Add Paper',
            'path' => 'compose'
        ]);
    }
}
