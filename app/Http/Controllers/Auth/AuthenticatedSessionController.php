<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $role = $request->input('role', 'member');
        $credentials = $request->only('email', 'password');
        
        // Check if this is an admin login first
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        
        // Handle trainer login
        if ($role === 'trainer') {
            // Check if user exists in trainers table with matching email
            $trainer = Trainer::where('email', $request->email)->first();
            
            if ($trainer && Hash::check($request->password, $trainer->password)) {
                // Check if trainer is active
                if ($trainer->status !== 'Active') {
                    return back()->withErrors([
                        'email' => 'Your trainer account is inactive. Please contact administrator.',
                    ])->onlyInput('email');
                }
                
                // Create or get user record for this trainer
                $user = User::where('email', $request->email)->first();
                
                if (!$user) {
                    // Create a user record for this trainer if it doesn't exist
                    $user = User::create([
                        'first_name' => $trainer->first_name,
                        'middle_name' => $trainer->middle_name,
                        'last_name' => $trainer->last_name,
                        'email' => $trainer->email,
                        'phone' => $trainer->phone,
                        'password' => $trainer->password, // Already hashed
                        'role' => 'trainer',
                        'plan' => 'N/A',
                        'status' => 'Active',
                    ]);
                }
                
                // Log the user in
                Auth::login($user);
                $request->session()->regenerate();
                
                return redirect()->intended(route('trainer.dashboard'));
            }
            
            return back()->withErrors([
                'email' => 'Invalid trainer credentials.',
            ])->onlyInput('email');
        }
        
        // Handle member login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'member') {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
            Auth::logout();
            return back()->withErrors([
                'email' => 'Invalid member credentials.',
            ])->onlyInput('email');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}