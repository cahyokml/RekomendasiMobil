<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama (home page)
     */
    public function index()
    {
        return view('home');
    }
}
