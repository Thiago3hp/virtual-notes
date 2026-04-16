<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskViewController extends Controller
{
    public function index()
    {
        return Inertia:: render('Home-Tasks');
    }
}
