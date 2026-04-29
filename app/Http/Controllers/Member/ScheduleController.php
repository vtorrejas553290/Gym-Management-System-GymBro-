<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function getMySchedules()
    {
        $schedules = Schedule::with(['member', 'trainer'])
            ->where('member_id', Auth::id())
            ->where('payment_status', 'Paid')
            ->orderBy('session_date', 'desc')
            ->get();

        $formattedSchedules = $schedules->map(function($schedule) {
            return [
                'id' => $schedule->id,
                'memberName' => $schedule->member->first_name . ' ' . $schedule->member->last_name,
                'trainerName' => $schedule->trainer->first_name . ' ' . $schedule->trainer->last_name,
                'sessionType' => $schedule->session_type,
                'sessionDate' => $schedule->session_date,
                'sessionTime' => $schedule->session_time,
                'duration' => $schedule->duration,
                'location' => $schedule->location,
                'paymentStatus' => $schedule->payment_status,
                'status' => $schedule->status,
            ];
        });

        return response()->json($formattedSchedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'session_type' => 'required|string',
            'session_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required',
            'duration' => 'required|string',
            'location' => 'required|string',
            'amount' => 'required|numeric',
            'hours' => 'required|numeric',
        ]);

        $schedule = Schedule::create([
            'member_id' => Auth::id(),
            'trainer_id' => $request->trainer_id,
            'session_type' => $request->session_type,
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'duration' => $request->duration,
            'location' => $request->location,
            'payment_status' => 'Pending',
            'status' => 'Scheduled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session created successfully',
            'schedule' => $schedule
        ], 201);
    }

    public function reschedule(Request $request, $id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('member_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'session_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required',
        ]);

        $schedule->update([
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session rescheduled successfully'
        ]);
    }

    public function cancel($id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('member_id', Auth::id())
            ->firstOrFail();

        $schedule->update(['status' => 'Cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Session cancelled successfully'
        ]);
    }
}