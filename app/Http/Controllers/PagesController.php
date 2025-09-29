<?php

namespace App\Http\Controllers;
use App\Models\Pages;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() : View
    {
        return view('pages.index');
    }

    public function mainpage(): View
    {
        return view('pages.mainpage');
    }
     public function profile(): View
    {
        return view('pages.profile');
    }
}
