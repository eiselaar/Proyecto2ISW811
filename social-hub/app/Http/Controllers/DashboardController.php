<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

// Controlador que maneja la lógica del dashboard principal
class DashboardController extends Controller
{
    // Método que muestra la página principal del dashboard
    public function index(Request $request): View

    {   // Obtiene el usuario autenticado de la request
        $user = $request->user();

       // Obtiene la lista de plataformas sociales conectadas del usuario
       $connectedPlatforms = $user->socialAccounts()
           ->pluck('provider') // Extrae solo el nombre del proveedor 
           ->toArray();
        
       // Inicializa un array con estadísticas en 0
       $stats = [
        'total_posts' => 0,
        'queued_posts' => 0, 
        'scheduled_posts' => 0,
        'published_posts' => 0
    ];

       // Si hay un usuario autenticado, obtiene las estadísticas reales
       if ($user) {
        $stats = [
            'total_posts' => $user->posts()->count(), // Total de posts
            'queued_posts' => $user->posts()->where('status', 'queued')->count(), // Posts en cola
            'scheduled_posts' => $user->posts()->where('status', 'scheduled')->count(), // Posts programados
            'published_posts' => $user->posts()->where('status', 'published')->count(), // Posts publicados
        ];
    }

       // Obtiene los 5 posts más recientes del usuario
       $recentPosts = $user ? $user->posts()
           ->latest() // Ordena por fecha descendente
           ->take(5)  // Toma solo 5 registros
           ->get() : collect();

       // Obtiene los próximos 5 posts programados
       $upcomingPosts = $user ? $user->posts()
           ->where('status', 'scheduled') // Solo posts programados
           ->where('scheduled_at', '>', now()) // Que aún no hayan pasado
           ->orderBy('scheduled_at') // Ordena por fecha de programación
           ->take(5) // Toma solo 5 registros
           ->get() : collect();

        // Obtiene las plataformas conectadas del usuario
        $connectedPlatforms = $user ? $user->socialAccounts()
            ->pluck('provider')
            ->toArray() : [];

        // Retorna la vista 'dashboard' con todos los datos necesarios
        return view('dashboard', compact(
            'stats',
            'recentPosts',
            'upcomingPosts',
            'connectedPlatforms'
        ));
    }
}
