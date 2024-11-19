<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $recentPosts = $user->posts()->latest()->take(5)->get();
        $scheduledPosts = $user->posts()->whereHas('queuedPost')->get();
        
        return view('home', compact('recentPosts', 'scheduledPosts'));
    }
}