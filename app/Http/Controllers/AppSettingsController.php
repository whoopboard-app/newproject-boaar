<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }
}
