<?php

use App\Http\Controllers\ProfileController;
use App\Models\Admin;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Member\ScheduleController as MemberScheduleController;
use App\Http\Controllers\Trainer\ScheduleController as TrainerScheduleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Str;

// Helper function to generate unique payment ID
function generatePaymentId() {
    // Get the last payment ID from database
    $lastPayment = Payment::orderBy('id', 'desc')->first();
    if ($lastPayment) {
        // Extract the number from PAYXXX
        $lastNumber = intval(substr($lastPayment->payment_id, 3));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    return 'PAY' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

// ============ MAIN LOGIN ROUTE (Handles Member, Trainer, AND Admin) ============
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// ============ ADMIN ROUTES ============

Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', function () {
        Auth::guard('admin')->logout();
        return redirect('/');
    })->name('logout');
   
    // Admin Management Routes
    Route::get('/members', function () {
        return view('admin.members');
    })->name('members');
    
    // Members API Routes
    Route::get('/members/data', function () {
        $members = User::where('role', 'member')->get();
        return response()->json($members);
    })->name('members.data');

    Route::post('/members', function (Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'plan' => 'required|string',
            'status' => 'required|string',
        ]);
        
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'member',
            'plan' => $validated['plan'],
            'status' => $validated['status'],
        ]);
        
        return response()->json($user, 201);
    })->name('members.store');
    
    Route::put('/members/{id}', function (Request $request, $id) {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'plan' => 'required|string',
            'status' => 'required|string',
        ]);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'plan' => $validated['plan'],
            'status' => $validated['status'],
        ];
        
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }
        
        $user->update($updateData);
        return response()->json($user);
    })->name('members.update');
    
    Route::delete('/members/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    })->name('members.destroy');
    
    Route::get('/trainers', function () {
        return view('admin.trainers');
    })->name('trainers');
    
    // Trainers API Routes
    Route::get('/trainers/data', function () {
        $trainers = Trainer::all();
        return response()->json($trainers);
    })->name('trainers.data');
    
    Route::post('/trainers', function (Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'specialization' => 'required|string',
            'experience' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ]);
        
        $trainer = Trainer::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'specialization' => $validated['specialization'],
            'experience' => $validated['experience'],
            'hourly_rate' => $validated['hourly_rate'],
            'status' => $validated['status'],
        ]);
        
        return response()->json($trainer, 201);
    })->name('trainers.store');
    
    Route::put('/trainers/{id}', function (Request $request, $id) {
        $trainer = Trainer::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'specialization' => 'required|string',
            'experience' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ]);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'specialization' => $validated['specialization'],
            'experience' => $validated['experience'],
            'hourly_rate' => $validated['hourly_rate'],
            'status' => $validated['status'],
        ];
        
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }
        
        $trainer->update($updateData);
        return response()->json($trainer);
    })->name('trainers.update');
    
    Route::put('/trainers/{id}/rate', function (Request $request, $id) {
        $trainer = Trainer::findOrFail($id);
        $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
        ]);
        $trainer->update(['hourly_rate' => $request->hourly_rate]);
        return response()->json($trainer);
    })->name('trainers.update-rate');
    
    Route::delete('/trainers/{id}', function ($id) {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();
        return response()->json(null, 204);
    })->name('trainers.destroy');
    
    Route::get('/membership-plans', function () {
        return view('admin.membership-plans');
    })->name('membership-plans');
    
    // Membership Plans API Routes
    Route::get('/membership-plans/data', function () {
        $plans = MembershipPlan::all();
        return response()->json($plans);
    })->name('membership-plans.data');
    
    Route::post('/membership-plans', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'duration_days' => 'required|integer',
            'price' => 'required|numeric',
            'features' => 'required|array',
            'popular' => 'boolean',
            'active' => 'boolean',
        ]);
        
        $plan = MembershipPlan::create($validated);
        return response()->json($plan, 201);
    })->name('membership-plans.store');
    
    Route::put('/membership-plans/{id}', function (Request $request, $id) {
        $plan = MembershipPlan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'duration_days' => 'required|integer',
            'price' => 'required|numeric',
            'features' => 'required|array',
            'popular' => 'boolean',
            'active' => 'boolean',
        ]);
        
        $plan->update($validated);
        return response()->json($plan);
    })->name('membership-plans.update');
    
    Route::delete('/membership-plans/{id}', function ($id) {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();
        return response()->json(null, 204);
    })->name('membership-plans.destroy');
    
    // ============ TRAINER SCHEDULE ROUTES (Admin) ============
    Route::get('/trainer-schedule', [ScheduleController::class, 'index'])->name('trainer-schedule');
    Route::get('/schedules/data', [ScheduleController::class, 'getSchedules'])->name('schedules.data');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::put('/schedules/{id}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.update-status');
    Route::patch('/schedules/{id}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
    Route::get('/members/list', [ScheduleController::class, 'getMembers'])->name('members.list');
    Route::get('/trainers/list', [ScheduleController::class, 'getTrainers'])->name('trainers.list');
    
    // Admin Payments Routes
    Route::get('/payments', function () {
        return view('admin.payments');
    })->name('payments');
    
    // Admin Payments API Routes
    Route::get('/payments/data', function () {
        $payments = Payment::with('user')->get();
        return response()->json($payments);
    })->name('payments.data');
    
    Route::post('/payments', function (Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'status' => 'required|in:Paid,Pending,Overdue',
            'details' => 'nullable|string',
        ]);
        
        $payment = Payment::create([
            'payment_id' => generatePaymentId(),
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'method' => $validated['method'],
            'status' => $validated['status'],
            'details' => $validated['details'],
        ]);
        
        return response()->json($payment, 201);
    })->name('payments.store');
    
    Route::put('/payments/{id}', function (Request $request, $id) {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'status' => 'required|in:Paid,Pending,Overdue',
            'details' => 'nullable|string',
        ]);
        
        $payment->update($validated);
        return response()->json($payment);
    })->name('payments.update');
    
    Route::put('/payments/{id}/status', function (Request $request, $id) {
        $payment = Payment::findOrFail($id);
        $request->validate([
            'status' => 'required|in:Paid,Pending,Overdue',
        ]);
        $payment->update(['status' => $request->status]);
        return response()->json($payment);
    })->name('payments.update-status');
    
        // Admin update payment status and message (with schedule_id support)
    Route::put('/payments/{id}/update', function (Request $request, $id) {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:Paid,Pending,Overdue',
            'admin_message' => 'nullable|string',
        ]);
        
        $oldStatus = $payment->status;
        $payment->update([
            'status' => $validated['status'],
            'admin_message' => $validated['admin_message'] ?? null,
        ]);
        
        // If payment status changed to Paid
        if ($validated['status'] === 'Paid' && $oldStatus !== 'Paid') {
            // First try with schedule_id
            if ($payment->schedule_id) {
                $schedule = Schedule::find($payment->schedule_id);
                if ($schedule) {
                    $schedule->update(['payment_status' => 'Paid']);
                }
            } 
            // Fallback: try to find schedule for trainer session
            else if ($payment->type === 'Trainer Session') {
                $schedule = Schedule::where('member_id', $payment->user_id)
                    ->where('payment_status', 'Pending')
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($schedule) {
                    $schedule->update(['payment_status' => 'Paid']);
                    $payment->update(['schedule_id' => $schedule->id]);
                }
            }
            
            // UPDATE MEMBER'S MEMBERSHIP PLAN WHEN MEMBERSHIP PAYMENT IS PAID
            if ($payment->type === 'Membership' || $payment->type === 'Membership Renewal') {
                $user = User::find($payment->user_id);
                if ($user) {
                    // Extract plan name from payment details
                    $planName = null;
                    if ($payment->details) {
                        // Details format: "Premium Membership Plan - 3 Months" or "Basic Membership Plan - 1 Month"
                        if (preg_match('/^(\w+)\s+Membership/', $payment->details, $matches)) {
                            $planName = $matches[1];
                        }
                    }
                    
                    // If plan name found, update the user's plan
                    if ($planName) {
                        $user->update(['plan' => $planName]);
                    } else {
                        // Fallback: try to get from the plan name in user or default
                        $user->update(['plan' => $user->plan ?? 'Basic']);
                    }
                    
                    // Also set status to Active if inactive
                    if ($user->status !== 'Active') {
                        $user->update(['status' => 'Active']);
                    }
                }
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Payment updated successfully']);
    })->name('payments.update-status-message');
    
    Route::delete('/payments/{id}', function ($id) {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(null, 204);
    })->name('payments.destroy');
    
    Route::get('/attendance', function () {
        return view('admin.attendance');
    })->name('attendance');
    
    Route::get('/activity-logs', function () {
        return view('admin.activity-logs');
    })->name('activity-logs');
    
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
});

// ============ MEMBER & TRAINER ROUTES ============
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Member Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-schedule', function () {
        return view('my-schedule');
    })->name('my-schedule');
    
    // Member Schedule API Routes
    Route::get('/member/schedules', [MemberScheduleController::class, 'getMySchedules'])->name('member.schedules');
    Route::post('/member/schedules', [MemberScheduleController::class, 'store'])->name('member.schedules.store');
    Route::put('/member/schedules/{id}/reschedule', [MemberScheduleController::class, 'reschedule'])->name('member.schedules.reschedule');
    Route::patch('/member/schedules/{id}/cancel', [MemberScheduleController::class, 'cancel'])->name('member.schedules.cancel');
    
    Route::get('/membership', function () {
        return view('membership');
    })->name('membership');
    
    Route::get('/available-trainers', function () {
        return view('available-trainers');
    })->name('available-trainers');
    
    // Available Trainers API Route
    Route::get('/available-trainers/data', function () {
        $trainers = Trainer::where('status', 'Active')->get();
        return response()->json($trainers);
    })->name('available-trainers.data');
    
    Route::get('/my-trainer', function () {
        return view('my-trainer');
    })->name('my-trainer');
    
    // Member - Get trainer stats (total clients and sessions completed) - SIMPLIFIED
    Route::get('/member/trainer-stats/{trainerId}', function ($trainerId) {
        // Get total unique clients (members) who had sessions with this trainer
        $totalClients = \App\Models\Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->count('member_id');
        
        // Get total sessions for this trainer
        $sessionsCompleted = \App\Models\Schedule::where('trainer_id', $trainerId)->count();
        
        return response()->json([
            'total_clients' => $totalClients,
            'sessions_completed' => $sessionsCompleted
        ]);
    })->name('member.trainer.stats');
    
    Route::get('/payment', function () {
        return view('payment');
    })->name('payment');
    
    // Member Payment API Routes - GET payments (status reflects admin updates)
    Route::get('/member/payments', function () {
        $payments = Payment::where('user_id', Auth::id())->get();
        return response()->json($payments);
    })->name('member.payments');
    
    // Member Create Payment Route (for trainer sessions) - WITH schedule_id support
    Route::post('/member/payments', function (Request $request) {
        $validated = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'details' => 'nullable|string',
            'payment_date' => 'required|date',
            'method' => 'required|string',
            'status' => 'required|in:Paid,Pending,Overdue',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);
        
        $payment = Payment::create([
            'payment_id' => generatePaymentId(),
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'method' => 'GCash',
            'status' => 'Pending',
            'details' => $validated['details'] ?? null,
            'schedule_id' => $validated['schedule_id'] ?? null,
        ]);
        
        return response()->json($payment, 201);
    })->name('member.payments.store');
    
              // Member Payment - Record GCash payment details with proof image (status remains PENDING)
    Route::post('/member/payments/{id}/pay', function (Request $request, $id) {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'gcash_number' => 'required|string',
            'reference_number' => 'required|string',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,heic,heif|max:5120', // Max 5MB
        ]);
        
        $updateData = [
            'gcash_number' => $validated['gcash_number'],
            'reference_number' => $validated['reference_number'],
        ];
        
        // Handle file upload - Direct to public/uploads folder (no symlink needed)
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Create directory inside public
            $destinationPath = public_path('uploads/payment_proofs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            // Move file directly
            $file->move($destinationPath, $filename);
            $updateData['proof_image'] = '/uploads/payment_proofs/' . $filename;
        }
        
        $payment->update($updateData);
        
        return response()->json([
            'success' => true, 
            'message' => 'GCash payment recorded and pending admin approval',
            'payment' => $payment,
            'status' => $payment->status
        ]);
    })->name('member.payments.pay');
        // Cancel a pending payment (only if no reference number submitted yet)
    Route::delete('/member/payments/{id}/cancel', function ($id) {
        $payment = Payment::findOrFail($id);
        
        // Only allow cancellation if payment is Pending and no reference number (not submitted yet)
        if ($payment->status === 'Pending' && !$payment->reference_number) {
            // If this is a trainer session payment, also update the schedule
            if ($payment->type === 'Trainer Session' && $payment->schedule_id) {
                $schedule = Schedule::find($payment->schedule_id);
                if ($schedule) {
                    // Update schedule status to Cancelled
                    $schedule->update(['status' => 'Cancelled']);
                }
            }
            
            $payment->delete();
            return response()->json(['success' => true, 'message' => 'Payment cancelled successfully']);
        }
        
        return response()->json(['error' => 'Cannot cancel this payment'], 400);
    })->name('member.payments.cancel');
    // ============ PROFILE ROUTES ============
    Route::get('/profile', function () {
    return view('profile');
})->name('profile');
   Route::put('/profile', function (Request $request) {
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
})->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Custom Member Profile Page
    Route::get('/my-profile', function () {
        return view('profile');
    })->name('profile');
    
    // Member Profile API Routes
    Route::get('/member/profile/data', function () {
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
    })->name('member.profile.data');
    
    Route::put('/member/profile/update', function (Request $request) {
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
    })->name('member.profile.update');
    
    Route::get('/member/payments/recent', function () {
        $payments = Payment::where('user_id', Auth::id())
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($payments);
    })->name('member.payments.recent');
    

    // Member Membership API Routes
    Route::get('/member/membership/current', function () {
        $user = Auth::user();
        
        // Check if user has any paid membership
        $hasPaidMembership = Payment::where('user_id', $user->id)
            ->where('type', 'Membership')
            ->where('status', 'Paid')
            ->exists();
        
        // If no paid membership, return "No Active Membership"
        if (!$hasPaidMembership) {
            return response()->json([
                'plan_name' => 'No Active Membership',
                'duration' => 'N/A',
                'duration_days' => 0,
                'price' => 0,
                'status' => 'Inactive',
                'remaining_days' => 0,
                'start_date' => 'N/A',
                'expiry_date' => 'N/A',
            ]);
        }
        
        $planName = $user->plan ?? 'Basic';
        
        $plan = MembershipPlan::where('name', $planName)->first();
        
        if ($plan) {
            $durationDays = $plan->duration_days;
            $price = $plan->price;
            $durationText = $plan->duration;
        } else {
            $durationDays = 30;
            $price = 49;
            $durationText = '1 Month';
        }
        
        $paidMembershipPayments = Payment::where('user_id', $user->id)
            ->where('type', 'Membership')
            ->where('status', 'Paid')
            ->orderBy('payment_date', 'desc')
            ->get();
        
        $paidRenewalPayments = Payment::where('user_id', $user->id)
            ->where('type', 'Membership Renewal')
            ->where('status', 'Paid')
            ->orderBy('payment_date', 'desc')
            ->get();
        
        $allPaidMembershipPayments = $paidMembershipPayments->concat($paidRenewalPayments)->sortByDesc('payment_date');
        
        $startDate = null;
        $expiryDate = null;
        
        if ($allPaidMembershipPayments->isNotEmpty()) {
            $latestPayment = $allPaidMembershipPayments->first();
            $startDate = $latestPayment->payment_date->format('Y-m-d');
            $expiryDateObj = $latestPayment->payment_date->copy();
            $expiryDateObj->addDays($durationDays);
            $expiryDate = $expiryDateObj->format('Y-m-d');
            
            if (now()->gt($expiryDateObj)) {
                $startDate = now()->format('Y-m-d');
                $expiryDateObj = now()->copy()->addDays($durationDays);
                $expiryDate = $expiryDateObj->format('Y-m-d');
            }
        } else {
            $startDate = $user->created_at ? $user->created_at->format('Y-m-d') : date('Y-m-d');
            $expiryDateObj = now()->copy()->addDays($durationDays);
            $expiryDate = $expiryDateObj->format('Y-m-d');
        }
        
        $remainingDays = max(0, (strtotime($expiryDate) - time()) / 86400);
        
        return response()->json([
            'plan_name' => $planName,
            'duration' => $durationText,
            'duration_days' => $durationDays,
            'price' => $price,
            'status' => $user->status ?? 'Active',
            'remaining_days' => round($remainingDays),
            'start_date' => $startDate,
            'expiry_date' => $expiryDate,
        ]);
    })->name('member.membership.current');
    
    Route::get('/member/membership-plans', function () {
        $plans = MembershipPlan::where('active', true)->get();
        return response()->json($plans);
    })->name('member.membership-plans');
    
    Route::post('/member/membership/select', function (Request $request) {
    $request->validate([
        'plan_id' => 'required|exists:membership_plans,id',
    ]);
    
    $plan = MembershipPlan::findOrFail($request->plan_id);
    $user = Auth::user();
    // DO NOT UPDATE PLAN HERE - Only update after payment is confirmed
    
    Payment::create([
        'payment_id' => generatePaymentId(),
        'user_id' => $user->id,
        'type' => 'Membership',
        'amount' => $plan->price,
        'payment_date' => now(),
        'method' => 'GCash',
        'status' => 'Pending',
        'details' => "{$plan->name} Membership Plan - {$plan->duration}",
    ]);
    
    return response()->json(['success' => true, 'message' => 'Plan selected successfully. Payment pending admin approval.']);
})->name('member.membership.select');
    
   Route::post('/member/membership/renew', function () {
    $user = Auth::user();
    $plan = MembershipPlan::where('name', $user->plan)->first();
    
    if ($plan) {
        Payment::create([
            'payment_id' => generatePaymentId(),
            'user_id' => $user->id,
            'type' => 'Membership Renewal',
            'amount' => $plan->price,
            'payment_date' => now(),
            'method' => 'GCash',
            'status' => 'Pending',
            'details' => "{$plan->name} Membership Renewal - {$plan->duration}",
        ]);
    }
    
    return response()->json(['success' => true, 'message' => 'Membership renewal initiated. Payment pending admin approval.']);
})->name('member.membership.renew');
});
// Debug route to check schedule data structure
Route::get('/admin/debug-schedules', function () {
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
});
// ============ TRAINER ROUTES ============
Route::middleware(['auth'])->group(function () {
    // Trainer Views
    Route::get('/trainer/dashboard', function () {
        return view('trainer.dashboard');
    })->name('trainer.dashboard');
    
    Route::get('/trainer/my-schedule', function () {
        return view('trainer.my-schedule');
    })->name('trainer.my-schedule');
    
    Route::get('/trainer/my-clients', function () {
        return view('trainer.my-clients');
    })->name('trainer.my-clients');
    
    Route::get('/trainer/payments', function () {
        return view('trainer.payments');
    })->name('trainer.payments');
    
    Route::get('/trainer/profile', function () {
        return view('trainer.profile');
    })->name('trainer.profile');
    
    // ============ TRAINER DASHBOARD API ROUTES ============
    
    // Helper function to get trainer ID
    function getTrainerId() {
        $user = Auth::user();
        if (!$user) return null;
        
        $trainer = \App\Models\Trainer::where('email', $user->email)->first();
        
        if (!$trainer) {
            $trainer = \App\Models\Trainer::where('first_name', $user->first_name)
                ->where('last_name', $user->last_name)
                ->first();
        }
        
        return $trainer ? $trainer->id : null;
    }
    
    // Get dashboard stats
    Route::get('/trainer/stats', function () {
        $trainerId = getTrainerId();
        
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
    })->name('trainer.stats');
    
    // Get today's sessions
    Route::get('/trainer/today-sessions', function () {
        $trainerId = getTrainerId();
        
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
    })->name('trainer.today-sessions');
    
    // Get active clients for dashboard
    Route::get('/trainer/active-clients', function () {
        $trainerId = getTrainerId();
        
        if (!$trainerId) {
            return response()->json([]);
        }
        
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
    })->name('trainer.active-clients');
    
    // ============ TRAINER CLIENTS API ROUTES ============
    
    // Get all clients data for My Clients page
    Route::get('/trainer/clients/data', function () {
        $trainerId = getTrainerId();
        
        if (!$trainerId) {
            return response()->json([
                'clients' => [],
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'avgProgress' => 0]
            ]);
        }
        
        // Get unique member IDs that have sessions with this trainer
        $memberIds = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->pluck('member_id');
        
        $clients = User::whereIn('id', $memberIds)->get();
        
        $totalProgress = 0;
        $activeCount = 0;
        $inactiveCount = 0;
        $clientData = [];
        
        foreach ($clients as $client) {
            // Calculate sessions completed
            $sessionsCompleted = Schedule::where('trainer_id', $trainerId)
                ->where('member_id', $client->id)
                ->where('status', 'Completed')
                ->count();
            
            // Calculate total scheduled sessions
            $totalSessions = Schedule::where('trainer_id', $trainerId)
                ->where('member_id', $client->id)
                ->count();
            
            // Calculate progress percentage
            $progress = $totalSessions > 0 ? round(($sessionsCompleted / $totalSessions) * 100) : 0;
            $totalProgress += $progress;
            
            $status = $client->status ?? 'Active';
            if ($status === 'Active') {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
            
            // Get the first session date as start date
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
    })->name('trainer.clients.data');
    
    // View single client details
    Route::get('/trainer/clients/{id}', function ($id) {
        $client = User::findOrFail($id);
        return response()->json($client);
    })->name('trainer.clients.show');
    
    // ============ TRAINER SCHEDULE API ROUTES ============
    
    // Get all schedules for the logged-in trainer
    Route::get('/trainer/schedules/data', function () {
        $trainerId = getTrainerId();
        
        if (!$trainerId) {
            return response()->json([
                'schedules' => [],
                'stats' => ['total' => 0, 'scheduled' => 0, 'completed' => 0, 'pendingPayment' => 0]
            ]);
        }
        
        $query = Schedule::with(['member', 'trainer'])
            ->where('trainer_id', $trainerId);
        
        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhere('session_type', 'like', "%{$search}%");
        }
        
        // Apply date filter
        if (request('date')) {
            $query->whereDate('session_date', request('date'));
        }
        
        $schedules = $query->orderBy('session_date', 'desc')
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
                'memberName' => $schedule->member->first_name . ' ' . $schedule->member->last_name,
                'memberId' => $schedule->member_id,
                'trainerName' => $schedule->trainer->first_name . ' ' . $schedule->trainer->last_name,
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
    })->name('trainer.schedules.data');
    
    // Get list of members (for schedule assignment)
    Route::get('/trainer/members/list', function () {
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
    })->name('trainer.members.list');
    
    // Get list of active trainers
    Route::get('/trainer/trainers/list', function () {
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
    })->name('trainer.trainers.list');
    
    // Create new schedule
    Route::post('/trainer/schedules', function (Request $request) {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'trainer_id' => 'required|exists:trainers,id',
            'session_type' => 'required|string',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration' => 'required|string',
            'location' => 'required|string',
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
    })->name('trainer.schedules.store');
    
    // Update schedule status (for My Schedule page)
    Route::put('/trainer/schedules/{id}/status', function (Request $request, $id) {
        $schedule = Schedule::findOrFail($id);
        $validated = $request->validate(['status' => 'required|in:Scheduled,Completed,Cancelled']);
        $schedule->update(['status' => $validated['status']]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    })->name('trainer.schedules.update-status');
    
    // Cancel schedule
    Route::patch('/trainer/schedules/{id}/cancel', function ($id) {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'Cancelled']);
        return response()->json(['success' => true, 'message' => 'Session cancelled successfully']);
    })->name('trainer.schedules.cancel');
});

// ============ TRAINER PAYMENTS API ROUTES ============

// Get payments for the logged-in trainer (only payments related to their sessions)
Route::get('/trainer/payments/data', function () {
    $trainerId = getTrainerId();
    
    if (!$trainerId) {
        return response()->json([]);
    }
    
    // Get ALL schedule IDs that belong to this trainer
    $scheduleIds = Schedule::where('trainer_id', $trainerId)->pluck('id');
    
    // Get payments that are linked to these schedules via schedule_id
    // This ensures trainers only see payments for their own sessions
    $payments = Payment::whereIn('schedule_id', $scheduleIds)
        ->where('type', 'Trainer Session')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // If no payments found with schedule_id, try to find by member_id (fallback for old records)
    if ($payments->isEmpty()) {
        // Get all member IDs that have schedules with this trainer
        $memberIds = Schedule::where('trainer_id', $trainerId)
            ->distinct('member_id')
            ->pluck('member_id');
        
        // Get payments for those members (fallback)
        $payments = Payment::whereIn('user_id', $memberIds)
            ->where('type', 'Trainer Session')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    // Include member details directly in the response
    $paymentsWithDetails = $payments->map(function($payment) {
        $member = User::find($payment->user_id);
        $schedule = Schedule::find($payment->schedule_id);
        
        return [
            'id' => $payment->id,
            'payment_id' => $payment->payment_id,
            'user_id' => $payment->user_id,
            'member_name' => $member ? $member->first_name . ' ' . $member->last_name : 'Unknown',
            'member_first_name' => $member ? $member->first_name : '',
            'member_last_name' => $member ? $member->last_name : '',
            'type' => $payment->type,
            'amount' => $payment->amount,
            'payment_date' => $payment->payment_date,
            'method' => $payment->method,
            'gcash_number' => $payment->gcash_number,
            'reference_number' => $payment->reference_number,
            'proof_image' => $payment->proof_image,  // <-- ADD THIS LINE
            'status' => $payment->status,
            'details' => $payment->details,
            'admin_message' => $payment->admin_message,
            'schedule_id' => $payment->schedule_id,
            'session_date' => $schedule ? $schedule->session_date : null,
            'session_type' => $schedule ? $schedule->session_type : null,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ];
    });
    
    return response()->json($paymentsWithDetails);
})->name('trainer.payments.data');
// Trainer update payment status and message (only for payments related to their sessions)
Route::put('/trainer/payments/{id}/update', function (Request $request, $id) {
    $payment = Payment::findOrFail($id);
    $trainerId = getTrainerId();
    
    if (!$trainerId) {
        return response()->json(['error' => 'Trainer not found'], 404);
    }
    
    // Check if this payment belongs to a session with this trainer
    $schedule = Schedule::find($payment->schedule_id);
    if (!$schedule || $schedule->trainer_id != $trainerId) {
        return response()->json(['error' => 'Unauthorized - This payment does not belong to your sessions'], 403);
    }
    
    $validated = $request->validate([
        'status' => 'required|in:Paid,Pending,Overdue',
        'admin_message' => 'nullable|string',
    ]);
    
    $oldStatus = $payment->status;
    $payment->update([
        'status' => $validated['status'],
        'admin_message' => $validated['admin_message'] ?? null,
    ]);
    
    // If payment status changed to Paid, update the schedule payment status
    if ($validated['status'] === 'Paid' && $oldStatus !== 'Paid') {
        if ($schedule) {
            $schedule->update(['payment_status' => 'Paid']);
        }
    }
    
    return response()->json(['success' => true, 'message' => 'Payment updated successfully']);
})->name('trainer.payments.update');

// Trainer Profile API Routes
Route::get('/trainer/profile/data', function () {
    $user = Auth::user();
    
    // Find the trainer by email (since trainer uses email for login)
    $trainer = \App\Models\Trainer::where('email', $user->email)->first();
    
    if (!$trainer) {
        return response()->json(['error' => 'Trainer not found'], 404);
    }
    
    // Get stats
    $totalClients = \App\Models\Schedule::where('trainer_id', $trainer->id)
        ->distinct('member_id')
        ->count('member_id');
    
    $sessionsCompleted = \App\Models\Schedule::where('trainer_id', $trainer->id)
        ->where('status', 'Completed')
        ->count();
    
    // Parse certifications if stored as JSON
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
        'rating' => 4.9, // You can calculate this from reviews if you have a reviews table
    ]);
})->name('trainer.profile.data');

Route::put('/trainer/profile/update', function (Request $request) {
    $user = Auth::user();
    $trainer = \App\Models\Trainer::where('email', $user->email)->first();
    
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
    
    // Also update the user email if they're the same person
    if ($user->email === $trainer->getOriginal('email')) {
        $user->update(['email' => $validated['email']]);
    }
    
    return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
})->name('trainer.profile.update');
require __DIR__.'/auth.php';