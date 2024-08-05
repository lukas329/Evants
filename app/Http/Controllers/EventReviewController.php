<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventReviewController extends Controller
{
    public function index()
    {
        return view('events.review');
    }
}
