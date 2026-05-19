<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function getData()
    {
        $user = Auth::user();
        
        $sessionsCompleted = Schedule::where('member_id', $user->id)
            ->where('status', 'Completed')
            ->count();
        
        $upcomingSessions = Schedule::where('member_id', $user->id)
            ->where('session_date', '>=', today())
            ->where('status', 'Scheduled')
            ->count();
        
        $lastPayment = Payment::where('user_id', $user->id)
            ->where('status', 'Paid')
            ->latest()
            ->first();
        
        $nextPayment = $lastPayment ? date('F d, Y', strtotime($lastPayment->payment_date . ' +30 days')) : 'N/A';
        
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'plan' => $user->plan ?? 'Basic',
            'status' => $user->status ?? 'Active',
            'join_date' => $user->created_at ? $user->created_at->format('F d, Y') : date('F d, Y'),
            'sessions_completed' => $sessionsCompleted,
            'upcoming_sessions' => $upcomingSessions,
            'next_payment' => $nextPayment,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
        ]);
        
        $nameParts = explode(' ', trim($validated['name']));
        $firstName = $nameParts[0];
        $lastName = count($nameParts) > 1 ? end($nameParts) : '';
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
        
        $user->update([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        
        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
}