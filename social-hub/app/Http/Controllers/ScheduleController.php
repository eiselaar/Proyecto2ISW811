<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleStoreRequest;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = auth()->user()->schedules()
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get();

        return view('schedules.index', compact('schedules'));
    }

    public function store(ScheduleStoreRequest $request)
    {
        auth()->user()->schedules()->create($request->validated());

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }
    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}