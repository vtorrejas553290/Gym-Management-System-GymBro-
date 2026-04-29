<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function getSchedules()
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
                ],
                'error' => 'Trainer not found'
            ]);
        }
        
        $schedules = Schedule::with(['member', 'trainer'])
            ->where('trainer_id', $trainerId)
            ->orderBy('session_date', 'desc')
            ->orderBy('session_time', 'asc')
            ->get();
        
        $stats = [
            'total' => Schedule::where('trainer_id', $trainerId)->count(),
            'scheduled' => Schedule::where('trainer_id', $trainerId)->where('status', 'Scheduled')->count(),
            'completed' => Schedule::where('trainer_id', $trainerId)->where('status', 'Completed')->count(),
            'pendingPayment' => Schedule::where('trainer_id', $trainerId)->where('payment_status', 'Pending')->count(),
        ];
        
        $formattedSchedules = $schedules->map(function($schedule) {
            return [
                'id' => 'TS' . str_pad($schedule->id, 3, '0', STR_PAD_LEFT),
                'originalId' => $schedule->id,
                'memberName' => $schedule->member ? $schedule->member->first_name . ' ' . $schedule->member->last_name : 'Unknown Member',
                'memberId' => $schedule->member_id,
                'trainerName' => $schedule->trainer ? $schedule->trainer->first_name . ' ' . $schedule->trainer->last_name : 'Unknown Trainer',
                'trainerId' => $schedule->trainer_id,
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
     * Get list of all members (for assign modal)
     */
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

    /**
     * Get list of all active trainers (for assign modal)
     */
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

    /**
     * Store a new schedule
     */
    public function store(Request $request)
    {
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
    }

    /**
     * Update an existing schedule
     */
    public function update(Request $request, $id)
    {
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
    }

    /**
     * Cancel a schedule
     */
    public function cancel($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'Cancelled']);
        
        return response()->json([
            'success' => true,
            'message' => 'Session cancelled successfully'
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([
                'total_clients' => 0,
                'total_clients_growth' => 0,
                'today_sessions' => 0,
                'remaining_today' => 0,
                'completed_sessions' => 0,
                'completed_this_week' => 0,
                'pending_sessions' => 0,
            ]);
        }
        
        return response()->json([
            'total_clients' => Schedule::where('trainer_id', $trainerId)->distinct('member_id')->count('member_id'),
            'total_clients_growth' => 5,
            'today_sessions' => Schedule::where('trainer_id', $trainerId)->whereDate('session_date', today())->where('status', 'Scheduled')->count(),
            'remaining_today' => Schedule::where('trainer_id', $trainerId)->whereDate('session_date', today())->where('status', 'Scheduled')->where('session_time', '>', now()->format('H:i'))->count(),
            'completed_sessions' => Schedule::where('trainer_id', $trainerId)->where('status', 'Completed')->count(),
            'completed_this_week' => Schedule::where('trainer_id', $trainerId)->where('status', 'Completed')->whereBetween('session_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'pending_sessions' => Schedule::where('trainer_id', $trainerId)->where('payment_status', 'Pending')->count(),
        ]);
    }

    /**
     * Get today's sessions for the dashboard
     */
    public function getTodaySessions()
    {
        $trainerId = $this->getTrainerId();
        
        $sessions = Schedule::with('member')
            ->where('trainer_id', $trainerId)
            ->whereDate('session_date', today())
            ->orderBy('session_time', 'asc')
            ->get();
        
        return response()->json($sessions->map(function($session) {
            return [
                'id' => $session->id,
                'time' => $session->session_time,
                'member_name' => $session->member ? $session->member->first_name . ' ' . $session->member->last_name : 'Unknown',
                'session_type' => $session->session_type,
                'duration' => $session->duration,
                'status' => $session->status,
            ];
        }));
    }

    /**
     * Get active clients for the dashboard
     */
    public function getActiveClients()
    {
        $trainerId = $this->getTrainerId();
        
        $memberIds = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->pluck('member_id');
        
        $clients = User::whereIn('id', $memberIds)
            ->where('status', 'Active')
            ->get();
        
        return response()->json($clients->map(function($client) use ($trainerId) {
            $nextSession = Schedule::where('member_id', $client->id)
                ->where('trainer_id', $trainerId)
                ->where('session_date', '>=', today())
                ->where('status', 'Scheduled')
                ->orderBy('session_date', 'asc')
                ->first();
            
            return [
                'id' => $client->id,
                'name' => $client->first_name . ' ' . $client->last_name,
                'initials' => strtoupper(substr($client->first_name, 0, 1)) . strtoupper(substr($client->last_name, 0, 1)),
                'plan' => $client->plan ?? 'Basic',
                'next_session' => $nextSession ? $nextSession->session_date->format('D, M d') . ' at ' . $nextSession->session_time : 'Not scheduled',
            ];
        }));
    }

    /**
     * Get weekly sessions data for chart
     */
    public function getWeeklySessions()
    {
        $trainerId = $this->getTrainerId();
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $sessions = [];
        
        foreach ($days as $index => $day) {
            $date = now()->startOfWeek()->addDays($index);
            $sessions[] = Schedule::where('trainer_id', $trainerId)
                ->whereDate('session_date', $date)
                ->count();
        }
        
        return response()->json([
            'labels' => $days,
            'sessions' => $sessions
        ]);
    }

    /**
     * Get client growth data for chart
     */
    public function getClientGrowth()
    {
        $trainerId = $this->getTrainerId();
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');
            $data[] = Schedule::where('trainer_id', $trainerId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->distinct('member_id')
                ->count('member_id');
        }
        
        return response()->json([
            'labels' => $months,
            'clients' => $data
        ]);
    }
}