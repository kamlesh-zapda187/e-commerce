<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SiteController;
use Illuminate\Http\Request;

class HomeController extends SiteController
{
    function index(){
        return $this->render_view('home.index');    
        //return view('home/index');
    }
}
