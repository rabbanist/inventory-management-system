<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        return view('pages.dashboard.dashboard');
    }


    public function profile(): View
    {
        return view('pages.dashboard.profile');
    }


    public function category(): View
    {
        return view('pages.dashboard.category');
    }

    public function product(): View
    {
        return view('pages.dashboard.product');
    }

    public function customer(): View
    {
        return view('pages.dashboard.customer');
    }


    public function invoice(): View
    {
        return view('pages.dashboard.invoice');
    }

    public function report(): View
    {
        return view('pages.dashboard.report');
    }

    public function sale(): View
    {
        return view('pages.dashboard.sale');
    }
}
