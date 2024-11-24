<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Inicializar las estadísticas con valores predeterminados
        $stats = [
            'total_posts' => 0,
            'queued_posts' => 0,
            'scheduled_posts' => 0,
            'published_posts' => 0
        ];

        // Si el usuario está autenticado, obtener las estadísticas reales
        if ($user) {
            $stats = [
                'total_posts' => $user->posts()->count(),
                'queued_posts' => $user->posts()->where('status', 'queued')->count(),
                'scheduled_posts' => $user->posts()->where('status', 'scheduled')->count(),
                'published_posts' => $user->posts()->where('status', 'published')->count(),
            ];
        }

        $recentPosts = $user ? $user->posts()
            ->latest()
            ->take(5)
            ->get() : collect();

        $upcomingPosts = $user ? $user->posts()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get() : collect();

        $connectedPlatforms = $user ? $user->socialAccounts()
            ->pluck('provider')
            ->toArray() : [];

        return view('dashboard', compact(
            'stats',
            'recentPosts',
            'upcomingPosts',
            'connectedPlatforms'
        ));
    }
}
