<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\Schedule;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        return view('available-trainers');
    }

    public function indexMyTrainer()
    {
        return view('my-trainer');
    }

    public function getAvailableTrainers()
    {
        $trainers = Trainer::where('status', 'Active')->get();
        return response()->json($trainers);
    }

    public function getTrainerStats($trainerId)
    {
        $totalClients = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->count('member_id');
        
        $sessionsCompleted = Schedule::where('trainer_id', $trainerId)->count();
        
        return response()->json([
            'total_clients' => $totalClients,
            'sessions_completed' => $sessionsCompleted
        ]);
    }
}