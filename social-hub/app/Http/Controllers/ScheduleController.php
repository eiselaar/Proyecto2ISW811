<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleStoreRequest; 
use Illuminate\Http\Request;

// Controlador para manejar los horarios de publicación
class ScheduleController extends Controller 
{
   // Muestra la lista de horarios del usuario
   public function index()
   {
       // Obtiene los horarios ordenados por día y hora
       $schedules = auth()->user()->schedules()
           ->orderBy('day_of_week') // Primero ordena por día de la semana
           ->orderBy('time')        // Luego ordena por hora
           ->get();

       return view('schedules.index', compact('schedules'));
   }

   // Almacena un nuevo horario
   public function store(ScheduleStoreRequest $request)
   {
       // Crea un nuevo horario para el usuario autenticado 
       // usando los datos validados por ScheduleStoreRequest
       auth()->user()->schedules()->create($request->validated());

       return redirect()->route('schedules.index')
           ->with('success', 'Schedule created successfully.');
   }

   // Actualiza un horario existente
   public function update(Request $request, Schedule $schedule)
   {
       // Valida los datos de entrada
       $validated = $request->validate([
           'time' => 'required|date_format:H:i',        // Formato hora:minutos
           'day_of_week' => 'required|integer|between:0,6', // Día de la semana (0=domingo a 6=sábado)
       ]);

       // Actualiza el horario con los datos validados
       $schedule->update($validated);

       return redirect()->back()->with('success', 'Horario actualizado correctamente');
   }

   // Elimina un horario
   public function destroy(Schedule $schedule)
   {
       // Elimina el horario de la base de datos
       $schedule->delete();
       
       return redirect()->back()->with('success', 'Horario eliminado correctamente');
   }
}