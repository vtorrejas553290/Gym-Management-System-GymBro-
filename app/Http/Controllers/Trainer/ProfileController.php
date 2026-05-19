<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('trainer.profile');
    }

    public function getData()
    {
        $user = Auth::user();
        
        $trainer = Trainer::where('email', $user->email)->first();
        
        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }
        
        $totalClients = Schedule::where('trainer_id', $trainer->id)
            ->distinct('member_id')
            ->count('member_id');
        
        $sessionsCompleted = Schedule::where('trainer_id', $trainer->id)
            ->where('status', 'Completed')
            ->count();
        
        $certifications = $trainer->certifications;
        if (is_string($certifications)) {
            $certifications = json_decode($certifications, true);
        }
        if (!$certifications) {
            $certifications = [];
        }
        
        return response()->json([
            'id' => $trainer->id,
            'first_name' => $trainer->first_name,
            'middle_name' => $trainer->middle_name,
            'last_name' => $trainer->last_name,
            'email' => $trainer->email,
            'phone' => $trainer->phone,
            'specialization' => $trainer->specialization,
            'experience' => $trainer->experience,
            'hourly_rate' => $trainer->hourly_rate,
            'bio' => $trainer->bio ?? 'Certified personal trainer with years of experience in fitness and strength training.',
            'location' => $trainer->location ?? 'Main Branch',
            'join_date' => $trainer->created_at ? $trainer->created_at->format('F d, Y') : date('F d, Y'),
            'certifications' => $certifications,
            'total_clients' => $totalClients,
            'sessions_completed' => $sessionsCompleted,
            'rating' => 4.9,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $trainer = Trainer::where('email', $user->email)->first();
        
        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);
        
        $nameParts = explode(' ', trim($validated['name']));
        $firstName = $nameParts[0];
        $lastName = count($nameParts) > 1 ? end($nameParts) : '';
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
        
        $trainer->update([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'specialization' => $validated['specialization'],
            'bio' => $validated['bio'],
        ]);
        
        if ($user->email === $trainer->getOriginal('email')) {
            $user->update(['email' => $validated['email']]);
        }
        
        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
}