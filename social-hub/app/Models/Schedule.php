<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Modelo que representa los horarios programados para publicación
class Schedule extends Model
{
   // Campos que se pueden asignar masivamente
   protected $fillable = [
       'user_id',      // ID del usuario dueño del horario
       'day_of_week',  // Día de la semana (0-6)
       'time',         // Hora del día
       'is_active',    // Si el horario está activo
   ];

   // Define el casteo de tipos para ciertos campos
   protected $casts = [
       'is_active' => 'boolean',  // Convierte a booleano
       'time' => 'datetime',      // Convierte a objeto Carbon
   ];

   // Relación con el usuario dueño del horario
   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }

   // Calcula la próxima ocurrencia de este horario
   public function getNextOccurrence()
   {
       $now = now(); // Obtiene la fecha/hora actual
       $scheduleTime = today()
           ->setTimeFromTimeString($this->time->format('H:i:s')); // Establece la hora programada
       
       // Avanza días hasta encontrar el próximo día válido
       while ($scheduleTime->dayOfWeek !== $this->day_of_week || $scheduleTime <= $now) {
           $scheduleTime->addDay();
       }

       return $scheduleTime;
   }
}