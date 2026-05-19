<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function getTrainerId()
    {
        $user = Auth::user();
        if (!$user) return null;
        
        $trainer = Trainer::where('email', $user->email)->first();
        
        if (!$trainer) {
            $trainer = Trainer::where('first_name', $user->first_name)
                ->where('last_name', $user->last_name)
                ->first();
        }
        
        return $trainer ? $trainer->id : null;
    }

    public function index()
    {
        return view('trainer.dashboard');
    }

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
        
        $totalClients = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->count('member_id');
        
        $todaySessions = Schedule::where('trainer_id', $trainerId)
            ->whereDate('session_date', today())
            ->where('status', 'Scheduled')
            ->count();
        
        $remainingToday = Schedule::where('trainer_id', $trainerId)
            ->whereDate('session_date', today())
            ->where('status', 'Scheduled')
            ->where('session_time', '>', now()->format('H:i'))
            ->count();
        
        $completedSessions = Schedule::where('trainer_id', $trainerId)
            ->where('status', 'Completed')
            ->count();
        
        $completedThisWeek = Schedule::where('trainer_id', $trainerId)
            ->where('status', 'Completed')
            ->whereBetween('session_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        
        $pendingSessions = Schedule::where('trainer_id', $trainerId)
            ->where('payment_status', 'Pending')
            ->count();
        
        $clientGrowth = Schedule::where('trainer_id', $trainerId)
            ->where('created_at', '>=', now()->subDays(30))
            ->distinct('member_id')
            ->count('member_id');
        
        return response()->json([
            'total_clients' => $totalClients,
            'total_clients_growth' => $clientGrowth,
            'today_sessions' => $todaySessions,
            'remaining_today' => $remainingToday,
            'completed_sessions' => $completedSessions,
            'completed_this_week' => $completedThisWeek,
            'pending_sessions' => $pendingSessions,
        ]);
    }

    public function getTodaySessions()
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([]);
        }
        
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

    public function getActiveClients()
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([]);
        }
        
        $memberIds = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->pluck('member_id');
        
        $clients = \App\Models\User::whereIn('id', $memberIds)
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
}