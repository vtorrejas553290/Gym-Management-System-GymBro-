<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->orderBy('first_name')->get();
        $trainers = Trainer::where('status', 'Active')->orderBy('first_name')->get();
        
        return view('admin.trainer-schedule', compact('members', 'trainers'));
    }

    public function getSchedules(Request $request)
    {
        $query = Schedule::with(['member', 'trainer']);

        // Apply filters
        if ($request->search) {
            $query->whereHas('member', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->search . '%');
            })->orWhereHas('trainer', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->search . '%');
            })->orWhere('session_type', 'like', '%' . $request->search . '%');
        }

        if ($request->date) {
            $query->where('session_date', $request->date);
        }

        if ($request->trainer_id && $request->trainer_id != 'All') {
            $query->where('trainer_id', $request->trainer_id);
        }

        // Add status filter
        if ($request->status && $request->status != 'All') {
            $query->where('status', $request->status);
        }

        $schedules = $query->orderBy('session_date', 'desc')->get();



        // Format data for frontend
        $formattedSchedules = $schedules->map(function($schedule) {
            return [
                'id' => 'TS' . str_pad($schedule->id, 3, '0', STR_PAD_LEFT),
                'memberName' => $schedule->member->first_name . ' ' . $schedule->member->last_name,
                'trainerName' => $schedule->trainer->first_name . ' ' . $schedule->trainer->last_name,
                'sessionType' => $schedule->session_type,
                'sessionDate' => $schedule->session_date,
                'sessionTime' => $schedule->session_time,
                'duration' => $schedule->duration,
                'location' => $schedule->location,
                'paymentStatus' => $schedule->payment_status,
                'status' => $schedule->status,
                'memberId' => $schedule->member_id,
                'trainerId' => $schedule->trainer_id,
            ];
        });

        // Get stats
        $stats = [
            'total' => Schedule::count(),
            'scheduled' => Schedule::where('status', 'Scheduled')->count(),
            'completed' => Schedule::where('status', 'Completed')->count(),
            'pendingPayment' => Schedule::where('payment_status', 'Pending')->count(),
        ];

        return response()->json([
            'schedules' => $formattedSchedules,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'trainer_id' => 'required|exists:trainers,id',
            'session_type' => 'required|string',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration' => 'required|string',
            'location' => 'required|string',
            'payment_status' => 'required|in:Pending,Paid',
        ]);

        $schedule = Schedule::create([
            'member_id' => $request->member_id,
            'trainer_id' => $request->trainer_id,
            'session_type' => $request->session_type,
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'duration' => $request->duration,
            'location' => $request->location,
            'payment_status' => $request->payment_status,
            'status' => 'Scheduled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'schedule' => $schedule
        ]);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'member_id' => 'required|exists:users,id',
            'trainer_id' => 'required|exists:trainers,id',
            'session_type' => 'required|string',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration' => 'required|string',
            'location' => 'required|string',
            'payment_status' => 'required|in:Pending,Paid',
        ]);

        $schedule->update([
            'member_id' => $request->member_id,
            'trainer_id' => $request->trainer_id,
            'session_type' => $request->session_type,
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'duration' => $request->duration,
            'location' => $request->location,
            'payment_status' => $request->payment_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:Scheduled,Completed,Cancelled'
            ]);
            
            $schedule->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'Cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Session cancelled successfully'
        ]);
    }

    public function getMembers()
    {
        $members = User::where('role', 'member')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->first_name . ' ' . $member->last_name
                ];
            });

        return response()->json($members);
    }

    public function getTrainers()
    {
        $trainers = Trainer::where('status', 'Active')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function($trainer) {
                return [
                    'id' => $trainer->id,
                    'name' => $trainer->first_name . ' ' . $trainer->last_name
                ];
            });

        return response()->json($trainers);
    }
}