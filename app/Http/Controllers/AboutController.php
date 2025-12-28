<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AboutController extends Controller
{
    public function viewAbout()
    {
        return inertia('About');
    }
}

