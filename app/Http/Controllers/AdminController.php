<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function users ()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function transactions()
    {
        return view('admin.transactions.index');
    }
}
