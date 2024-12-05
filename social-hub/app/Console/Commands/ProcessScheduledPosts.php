<?php

namespace App\Console\Commands;

use App\Jobs\PublishPost;
use App\Models\Post;
use App\Models\QueuedPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

// Este comando se encarga de procesar los posts que están en cola para publicación
class ProcessScheduledPosts extends Command
{
    // Define el nombre del comando: php artisan posts:process-scheduled
    protected $signature = 'posts:process-scheduled';
    // Describe el propósito del comando
    protected $description = 'Process scheduled posts';

    public function handle()
    {
        // SECCIÓN 1: Procesar posts programados para hora específica
        // Busca todos los posts que:
        // - Están marcados como programados (is_scheduled = true)
        // - Ya pasó su hora programada (scheduled_for <= ahora)
        $scheduledPosts = QueuedPost::with('post')
            ->where('is_scheduled', true)
            ->where('scheduled_for', '<=', now())
            ->get();

        // Procesa cada post programado encontrado
        foreach ($scheduledPosts as $queuedPost) {
            // Registra la actividad en los logs
            Log::info('Processing scheduled post', [
                'post_id' => $queuedPost->post_id,
                'scheduled_for' => $queuedPost->scheduled_for
            ]);

            // Envía el post al job de publicación
            PublishPost::dispatch($queuedPost->post);
            // Elimina el post de la cola una vez procesado
            $queuedPost->delete();
        }

        // SECCIÓN 2: Procesar posts en cola general (sin hora específica)
        // Busca todos los posts que:
        // - Tienen estado 'queued'
        // - Están en la cola pero NO programados (is_scheduled = false)
        $queuedPosts = Post::where('status', 'queued')
            ->whereHas('queuedPost', function ($query) {
                $query->where('is_scheduled', false);
            })
            ->get();

        // Procesa cada post de la cola general
        foreach ($queuedPosts as $post) {
            // Registra la actividad en los logs
            Log::info('Processing queued post', [
                'post_id' => $post->id
            ]);

            // Envía el post al job de publicación
            PublishPost::dispatch($post);
            // Elimina el post de la cola una vez procesado
            $post->queuedPost->delete();
        }
    }
}