<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Modelo que representa un post en la aplicación
class Post extends Model
{
   // Habilita la creación de factories para pruebas
   use HasFactory;

   // Campos que se pueden asignar masivamente
   protected $fillable = [
       'user_id',          // ID del usuario que creó el post
       'content',          // Contenido del post
       'platforms',        // Plataformas donde se publicará (array)
       'status',           // Estado del post (draft, queued, etc)
       'scheduled_at',     // Fecha programada de publicación
       'published_at'      // Fecha de publicación real
   ];

   // Define el casteo de tipos para ciertos campos
   protected $casts = [
       'platforms' => 'array',        // Convierte JSON a array de PHP
       'scheduled_at' => 'datetime',  // Convierte a objeto Carbon
       'published_at' => 'datetime',  // Convierte a objeto Carbon
   ];

   // Relación con el usuario que creó el post
   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }

   // Relación con el registro de cola (si está en cola)
   public function queuedPost()
   {
       return $this->hasOne(QueuedPost::class);
   }

   // Scopes para filtrar posts por estado
   public function scopeDraft($query)     // Posts en borrador
   {
       return $query->where('status', 'draft');
   }

   public function scopeQueued($query)    // Posts en cola
   {
       return $query->where('status', 'queued');
   }

   public function scopeScheduled($query) // Posts programados
   {
       return $query->where('status', 'scheduled');
   }

   public function scopePublished($query) // Posts publicados
   {
       return $query->where('status', 'published');
   }

   public function scopeFailed($query)    // Posts que fallaron
   {
       return $query->where('status', 'failed');
   }
}