<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
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

        notify(t_db('general', 'system_language_changed_successfully'), t_db('general','success'));
        return redirect()->back();
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');

        return response()->json([
            'message' => t_db('general','cache_cleared_successfully')
        ]);
    }
}
