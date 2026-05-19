<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Get the trainer ID from the logged-in user
     */
    private function getTrainerId()
    {
        $user = Auth::user();
        if (!$user) return null;
        
        // Find trainer by email first
        $trainer = Trainer::where('email', $user->email)->first();
        
        // If not found by email, try by name
        if (!$trainer) {
            $trainer = Trainer::where('first_name', $user->first_name)
                ->where('last_name', $user->last_name)
                ->first();
        }
        
        return $trainer ? $trainer->id : null;
    }

    /**
     * Display the trainer schedule view
     */
    public function index()
    {
        return view('trainer.my-schedule');
    }

    /**
     * Get all schedules for the logged-in trainer (API)
     */
    public function getSchedules(Request $request)
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([
                'schedules' => [],
                'stats' => [
                    'total' => 0,
                    'scheduled' => 0,
                    'completed' => 0,
                    'pendingPayment' => 0
                ]
            ]);
        }
        
        $query = Schedule::with(['member', 'trainer'])
            ->where('trainer_id', $trainerId);
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('member', function($memberQuery) use ($search) {
                    $memberQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhere('session_type', 'like', "%{$search}%");
            });
        }
        
        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('session_date', $request->date);
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $schedules = $query->orderBy('session_date', 'desc')
            ->orderBy('session_time', 'asc')
            ->get();
        
        // Calculate stats based on filtered results
        $stats = [
            'total' => $schedules->count(),
            'scheduled' => $schedules->where('status', 'Scheduled')->count(),
            'completed' => $schedules->where('status', 'Completed')->count(),
            'pendingPayment' => $schedules->where('payment_status', 'Pending')->count(),
        ];
        
        $formattedSchedules = $schedules->map(function($schedule) {
            return [
                'id' => 'TS' . str_pad($schedule->id, 3, '0', STR_PAD_LEFT),
                'memberName' => $schedule->member 
                    ? $schedule->member->first_name . ' ' . $schedule->member->last_name 
                    : 'Unknown Member',
                'sessionType' => $schedule->session_type,
                'sessionDate' => $schedule->session_date,
                'sessionTime' => $schedule->session_time,
                'duration' => $schedule->duration,
                'location' => $schedule->location,
                'paymentStatus' => $schedule->payment_status,
                'status' => $schedule->status,
            ];
        });
        
        return response()->json([
            'schedules' => $formattedSchedules,
            'stats' => $stats
        ]);
    }

    /**
     * Update schedule status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $trainerId = $this->getTrainerId();
            
            // Verify this schedule belongs to the logged-in trainer
            if ($schedule->trainer_id != $trainerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This session does not belong to you'
                ], 403);
            }
            
            $validated = $request->validate([
                'status' => 'required|in:Scheduled,Completed,Cancelled'
            ]);
            
            $schedule->update(['status' => $validated['status']]);
            
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

    /**
     * Cancel a schedule
     */
    public function cancel($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $trainerId = $this->getTrainerId();
            
            // Verify this schedule belongs to the logged-in trainer
            if ($schedule->trainer_id != $trainerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This session does not belong to you'
                ], 403);
            }
            
            $schedule->update(['status' => 'Cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Session cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling session: ' . $e->getMessage()
            ], 500);
        }
    }
}