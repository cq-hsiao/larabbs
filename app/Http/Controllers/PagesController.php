<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function root()
    {
//        dd(Auth::user()->hasVerifiedEmail());
        return view('pages.root');
    }
}
