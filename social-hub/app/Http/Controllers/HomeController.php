<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\SocialAccount;
use App\Models\Schedule; // Añadir esta importación

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Obtener estadísticas
        $scheduledPosts = Post::where('user_id', $user->id)
            ->where('status', 'queued')
            ->count();
    
        $publishedPosts = Post::where('user_id', $user->id)
            ->where('status', 'published')
            ->count();
    
        $connectedAccounts = SocialAccount::where('user_id', $user->id)
            ->count();
    
        // Obtener posts recientes
        $recentPosts = Post::where('user_id', $user->id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();
    
        // Obtener posts en cola
        $queuedPosts = Post::where('user_id', $user->id)
            ->where('status', 'queued')
            ->whereHas('queuedPost')
            ->with('queuedPost')
            ->latest()
            ->take(5)
            ->get();
    
        // Obtener horarios
        $schedules = Schedule::where('user_id', $user->id)
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get();
    
        return view('dashboard', compact(
            'scheduledPosts',
            'publishedPosts',
            'connectedAccounts',
            'recentPosts',
            'queuedPosts',
            'schedules'
        ));
    }
}