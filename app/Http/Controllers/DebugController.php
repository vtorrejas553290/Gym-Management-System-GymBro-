<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function schedules()
    {
        $schedules = Schedule::with(['member', 'trainer'])->get();
        
        return response()->json([
            'count' => $schedules->count(),
            'sample' => $schedules->take(3)->map(function($s) {
                return [
                    'id' => $s->id,
                    'member_id' => $s->member_id,
                    'trainer_id' => $s->trainer_id,
                    'member_name' => $s->member ? $s->member->first_name . ' ' . $s->member->last_name : null,
                    'trainer_name' => $s->trainer ? $s->trainer->first_name . ' ' . $s->trainer->last_name : null,
                    'session_date' => $s->session_date,
                ];
            }),
            'all_schedules' => $schedules->map(function($s) {
                return [
                    'id' => $s->id,
                    'member_id' => $s->member_id,
                    'trainer_id' => $s->trainer_id,
                ];
            })
        ]);
    }
}