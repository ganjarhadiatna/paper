<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DesignModel;

class ComposeController extends Controller
{
    function composeDesign($idpapers)
    {
        $image = DesignModel::GetAllDesign($idpapers,'asc');
        return view('compose.image', [
            'title' => 'Add Designs',
            'path' => 'compose',
            'idpapers' => $idpapers,
            'image' => $image
        ]);
    }
    function composePaper()
    {
        return view('compose.paper', [
            'title' => 'Add Paper',
            'path' => 'compose'
        ]);
    }
}
