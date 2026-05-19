<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
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
        return view('trainer.my-clients');
    }

    public function getData()
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([
                'clients' => [],
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'avgProgress' => 0]
            ]);
        }
        
        $memberIds = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->pluck('member_id');
        
        $clients = User::whereIn('id', $memberIds)->get();
        
        $totalProgress = 0;
        $activeCount = 0;
        $inactiveCount = 0;
        $clientData = [];
        
        foreach ($clients as $client) {
            $sessionsCompleted = Schedule::where('trainer_id', $trainerId)
                ->where('member_id', $client->id)
                ->where('status', 'Completed')
                ->count();
            
            $totalSessions = Schedule::where('trainer_id', $trainerId)
                ->where('member_id', $client->id)
                ->count();
            
            $progress = $totalSessions > 0 ? round(($sessionsCompleted / $totalSessions) * 100) : 0;
            $totalProgress += $progress;
            
            $status = $client->status ?? 'Active';
            if ($status === 'Active') {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
            
            $firstSession = Schedule::where('trainer_id', $trainerId)
                ->where('member_id', $client->id)
                ->orderBy('session_date', 'asc')
                ->first();
            
            $clientData[] = [
                'id' => $client->id,
                'name' => $client->first_name . ' ' . $client->last_name,
                'email' => $client->email,
                'phone' => $client->phone ?? 'N/A',
                'plan' => $client->plan ?? 'Basic Plan',
                'goal' => 'Fitness Training',
                'progress' => $progress,
                'sessionsCompleted' => $sessionsCompleted,
                'startDate' => $firstSession ? $firstSession->session_date->format('Y-m-d') : $client->created_at->format('Y-m-d'),
                'status' => $status,
            ];
        }
        
        $avgProgress = $clients->count() > 0 ? round($totalProgress / $clients->count()) : 0;
        
        return response()->json([
            'clients' => $clientData,
            'stats' => [
                'total' => $clients->count(),
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'avgProgress' => $avgProgress
            ]
        ]);
    }

    public function show($id)
    {
        $client = User::findOrFail($id);
        return response()->json($client);
    }
}