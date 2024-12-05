<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Modelo que representa las cuentas de redes sociales conectadas
class SocialAccount extends Model
{
   // Campos que se pueden asignar masivamente  
   protected $fillable = [
       'user_id',                  // ID del usuario dueño de la cuenta
       'provider',                 // Plataforma social (linkedin, mastodon, etc)
       'provider_id',              // ID único del usuario en la plataforma
       'provider_token',           // Token de acceso
       'provider_refresh_token',   // Token para renovar el acceso
       'token_expires_at',         // Fecha de expiración del token
   ];

   // Define el casteo de tipos para ciertos campos
   protected $casts = [
       'token_expires_at' => 'datetime',  // Convierte a objeto Carbon
   ];

   // Relación con el usuario dueño de la cuenta
   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }

   // Verifica si el token ha expirado
   public function isTokenExpired(): bool
   {
       return $this->token_expires_at && $this->token_expires_at->isPast();
   }

   // Verifica si el token necesita renovarse pronto
   public function needsTokenRefresh(): bool
   {
       if (!$this->token_expires_at) {
           return false;
       }

       // Verifica si expirará en los próximos 5 minutos
       return $this->token_expires_at->subMinutes(5)->isPast();
   }
}