<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Modelo que representa un post en cola de publicación
class QueuedPost extends Model
{
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'post_id',          // ID del post relacionado
        'scheduled_for',    // Fecha/hora programada para publicar
        'is_scheduled',     // Indica si está programado o solo en cola
        'attempts',         // Número de intentos de publicación
        'last_error',       // Último error ocurrido
    ];

    // Define el casteo de tipos para ciertos campos
    protected $casts = [
        'scheduled_for' => 'datetime',  // Convierte a objeto Carbon
        'is_scheduled' => 'boolean',    // Convierte a booleano
    ];

    // Relación con el post principal
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Verifica si debe intentar publicar de nuevo
    public function shouldRetry(): bool
    {
        return $this->attempts < 3; // Máximo 3 intentos
    }

    // Incrementa el contador de intentos
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    // Verifica si el post está listo para publicarse
    public function isReadyToPublish(): bool
    {
        if ($this->is_scheduled) {
            return $this->scheduled_for <= now(); // Si es programado, verifica la fecha
        }
        // Verifica horarios configurados
        $user = $this->post->user;
        $currentTime = now();
        $dayOfWeek = $currentTime->dayOfWeek;

        return $user->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('time', $currentTime->format('H:i'))
            ->exists();
    }
}