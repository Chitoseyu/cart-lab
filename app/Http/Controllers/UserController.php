<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function login_page()
    {

        return view('page.login');
        
    }
}
