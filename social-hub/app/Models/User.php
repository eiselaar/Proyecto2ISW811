<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Modelo que representa a los usuarios del sistema
class User extends Authenticatable
{
   // Traits para funcionalidades adicionales
   use HasApiTokens, Notifiable;

   // Campos que se pueden asignar masivamente
   protected $fillable = [
       'name',                  // Nombre del usuario
       'email',                 // Email (usado para login)
       'password',              // Contraseña encriptada
       'two_factor_secret',     // Secreto para 2FA
       'two_factor_enabled',    // Si tiene 2FA activado
   ];

   // Campos que no se deben mostrar en las respuestas
   protected $hidden = [
       'password',              // Oculta la contraseña
       'remember_token',        // Token de "recordarme"
       'two_factor_secret',     // Oculta el secreto 2FA
   ];

   // Define el casteo de tipos para ciertos campos
   protected $casts = [
       'email_verified_at' => 'datetime',    // Fecha de verificación de email
       'two_factor_enabled' => 'boolean',    // Estado de 2FA
   ];

   // Relación con los posts del usuario
   public function posts(): HasMany
   {
       return $this->hasMany(Post::class);
   }

   // Relación con las cuentas sociales del usuario
   public function socialAccounts(): HasMany
   {
       return $this->hasMany(SocialAccount::class);
   }

   // Relación con los horarios programados del usuario
   public function schedules(): HasMany
   {
       return $this->hasMany(Schedule::class);
   }
}
