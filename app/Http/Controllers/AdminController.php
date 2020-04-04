<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function users ()
    {
        return view('admin.users.index');
    }

    public function transactions()
    {
        return view('admin.transactions.index');
    }
}
