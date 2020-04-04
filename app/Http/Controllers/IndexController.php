<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class IndexController extends MainController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transferMoney ()
    {
        return view('transfer-money', [
            'users' => User::all()
        ]);
    }
}
