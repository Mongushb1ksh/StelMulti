<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function layout(){
        return view('layout');
    }
    public function home(){
        return view('home');
    }
}
