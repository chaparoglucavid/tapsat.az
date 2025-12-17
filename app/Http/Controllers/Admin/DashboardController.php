<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin-dashboard.dashboard');
    }

    public function changeLanguage($lang)
    {
        App::setLocale($lang);
        Session::put('locale', $lang);

        notify('Sistem dili dəyişdirildi.', 'Uğurlu');
        return redirect()->back();
    }
}
