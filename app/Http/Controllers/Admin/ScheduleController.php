<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display the admin trainer schedule view
     */
    public function index()
    {
        return view('admin.trainer-schedule');
    }

    /**
     * Get all schedules with filters (API)
     */
    public function getSchedules(Request $request)
    {
        try {
            Log::info('Admin getSchedules called', $request->all());
            
            $query = Schedule::with(['member', 'trainer']);
            
            // Apply search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('member', function($memberQuery) use ($search) {
                        $memberQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })->orWhereHas('trainer', function($trainerQuery) use ($search) {
                        $trainerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })->orWhere('session_type', 'like', "%{$search}%");
                });
            }
            
            // Apply date filter
            if ($request->has('date') && $request->date) {
                $query->whereDate('session_date', $request->date);
            }
            
            // Apply trainer filter
            if ($request->has('trainer_id') && $request->trainer_id && $request->trainer_id !== 'All') {
                $query->where('trainer_id', $request->trainer_id);
            }
            
            // Apply status filter
            if ($request->has('status') && $request->status && $request->status !== 'All') {
                $query->where('status', $request->status);
            }
            
            $schedules = $query->orderBy('session_date', 'desc')
                ->orderBy('session_time', 'asc')
                ->get();
            
            Log::info('Schedules found: ' . $schedules->count());
            
            // Calculate stats
            $stats = [
                'total' => Schedule::count(),
                'scheduled' => Schedule::where('status', 'Scheduled')->count(),
                'completed' => Schedule::where('status', 'Completed')->count(),
                'pendingPayment' => Schedule::where('payment_status', 'Pending')->count(),
            ];
            
            // Format schedules for the frontend
            $formattedSchedules = $schedules->map(function($schedule) {
                return [
                    'id' => 'TS' . str_pad($schedule->id, 3, '0', STR_PAD_LEFT),
                    'originalId' => $schedule->id,
                    'memberName' => $schedule->member ? $schedule->member->first_name . ' ' . $schedule->member->last_name : 'Unknown Member',
                    'memberId' => $schedule->member_id,
                    'trainerName' => $schedule->trainer ? $schedule->trainer->first_name . ' ' . $schedule->trainer->last_name : 'Unknown Trainer',
                    'trainerId' => $schedule->trainer_id,
                    'sessionType' => $schedule->session_type ?? 'Not specified',
                    'sessionDate' => $schedule->session_date,
                    'sessionTime' => $schedule->session_time,
                    'duration' => $schedule->duration ?? '1 hour',
                    'location' => $schedule->location ?? 'Main Gym',
                    'paymentStatus' => $schedule->payment_status ?? 'Pending',
                    'status' => $schedule->status ?? 'Scheduled',
                ];
            });
            
            return response()->json([
                'success' => true,
                'schedules' => $formattedSchedules,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getSchedules: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading schedules: ' . $e->getMessage(),
                'schedules' => [],
                'stats' => [
                    'total' => 0,
                    'scheduled' => 0,
                    'completed' => 0,
                    'pendingPayment' => 0
                ]
            ], 500);
        }
    }

    /**
     * Get list of all members (for assign modal)
     */
    public function getMembers()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in getMembers: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Get list of all active trainers
     */
    public function getTrainers()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in getTrainers: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Store a new schedule
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'member_id' => 'required|exists:users,id',
                'trainer_id' => 'required|exists:trainers,id',
                'session_type' => 'required|string|max:255',
                'session_date' => 'required|date',
                'session_time' => 'required|string',
                'duration' => 'required|string',
                'location' => 'required|string|max:255',
                'payment_status' => 'required|in:Pending,Paid',
            ]);
            
            $schedule = Schedule::create([
                'member_id' => $validated['member_id'],
                'trainer_id' => $validated['trainer_id'],
                'session_type' => $validated['session_type'],
                'session_date' => $validated['session_date'],
                'session_time' => $validated['session_time'],
                'duration' => $validated['duration'],
                'location' => $validated['location'],
                'payment_status' => $validated['payment_status'],
                'status' => 'Scheduled',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing schedule
     */
    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            
            $validated = $request->validate([
                'member_id' => 'required|exists:users,id',
                'trainer_id' => 'required|exists:trainers,id',
                'session_type' => 'required|string|max:255',
                'session_date' => 'required|date',
                'session_time' => 'required|string',
                'duration' => 'required|string',
                'location' => 'required|string|max:255',
                'payment_status' => 'required|in:Pending,Paid',
            ]);
            
            $schedule->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Schedule not found for update: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error updating schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:Scheduled,Completed,Cancelled'
            ]);
            
            $schedule->update(['status' => $validated['status']]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in updateStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a schedule
     */
    public function cancel($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update(['status' => 'Cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Session cancelled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in cancel: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling session: ' . $e->getMessage()
            ], 500);
        }
    }
}