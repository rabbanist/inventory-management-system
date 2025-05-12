<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }



    // Auth page return views 
    public function login(): View
    {
        return view('pages.auth.login');
    }

    public function registration(): View
    {
        return view('pages.auth.registration');
    }


    public function forgotPassword(): View
    {
        return view('pages.auth.reset-password');
    }

    public function sendOtp(): View
    {
        return view('pages.auth.send-otp');
    }


    public function resetPassword(): View
    {
        return view('pages.auth.reset-password');
    }

}
