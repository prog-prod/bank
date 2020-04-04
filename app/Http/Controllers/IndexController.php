<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends MainController
{

    public function index(){
        return view('index');
    }
    public function transferMoney () {
        return view('transfer-money');
    }
}
