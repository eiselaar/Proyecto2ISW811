<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $schedules = auth()->user()->schedules()
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get()
            ->groupBy('day_of_week');

        return view('schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'time' => 'required|date_format:H:i',
        ]);

        auth()->user()->schedules()->create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    public function toggleActive(Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        
        $schedule->update([
            'is_active' => !$schedule->is_active
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }
}