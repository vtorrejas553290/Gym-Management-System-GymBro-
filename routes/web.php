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
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Member\ScheduleController as MemberScheduleController;
use App\Http\Controllers\Member\MembershipController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\TrainerController as MemberTrainerController;
use App\Http\Controllers\Trainer\ScheduleController as TrainerScheduleController;
use App\Http\Controllers\Trainer\DashboardController;
use App\Http\Controllers\Trainer\ClientController;
use App\Http\Controllers\Trainer\PaymentController as TrainerPaymentController;
use App\Http\Controllers\Trainer\ProfileController as TrainerProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DebugController;
use Illuminate\Support\Str;

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
    Route::get('/members', [MemberController::class, 'index'])->name('members');
    Route::get('/members/data', [MemberController::class, 'getData'])->name('members.data');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
    
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers');
    Route::get('/trainers/data', [TrainerController::class, 'getData'])->name('trainers.data');
    Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
    Route::put('/trainers/{id}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::put('/trainers/{id}/rate', [TrainerController::class, 'updateRate'])->name('trainers.update-rate');
    Route::delete('/trainers/{id}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
    
    Route::get('/membership-plans', [MembershipPlanController::class, 'index'])->name('membership-plans');
    Route::get('/membership-plans/data', [MembershipPlanController::class, 'getData'])->name('membership-plans.data');
    Route::post('/membership-plans', [MembershipPlanController::class, 'store'])->name('membership-plans.store');
    Route::put('/membership-plans/{id}', [MembershipPlanController::class, 'update'])->name('membership-plans.update');
    Route::delete('/membership-plans/{id}', [MembershipPlanController::class, 'destroy'])->name('membership-plans.destroy');
    
    // Trainer Schedule Routes (Admin)
    Route::get('/trainer-schedule', [ScheduleController::class, 'index'])->name('trainer-schedule');
    Route::get('/schedules/data', [ScheduleController::class, 'getSchedules'])->name('schedules.data');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::put('/schedules/{id}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.update-status');
    Route::patch('/schedules/{id}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
    Route::get('/members/list', [ScheduleController::class, 'getMembers'])->name('members.list');
    Route::get('/trainers/list', [ScheduleController::class, 'getTrainers'])->name('trainers.list');
    
    // Admin Payments Routes
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments');
    Route::get('/payments/data', [AdminPaymentController::class, 'getData'])->name('payments.data');
    Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
    Route::put('/payments/{id}', [AdminPaymentController::class, 'update'])->name('payments.update');
    Route::put('/payments/{id}/status', [AdminPaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::put('/payments/{id}/update', [AdminPaymentController::class, 'updateStatusWithMessage'])->name('payments.update-status-message');
    Route::delete('/payments/{id}', [AdminPaymentController::class, 'destroy'])->name('payments.destroy');
    
    // Report API routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/members-data', [ReportController::class, 'getMembersData'])->name('reports.members-data');
    Route::get('/reports/payments-data', [ReportController::class, 'getPaymentsData'])->name('reports.payments-data');
    Route::get('/reports/trainers-data', [ReportController::class, 'getTrainersData'])->name('reports.trainers-data');
    Route::get('/reports/schedules-data', [ReportController::class, 'getSchedulesData'])->name('reports.schedules-data');

    // Report PDF
    Route::get('/export-pdf/{year}', [App\Http\Controllers\PDFExportController::class, 'exportPDF'])->name('export.pdf');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
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
    
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership');
    
    Route::get('/available-trainers', [MemberTrainerController::class, 'index'])->name('available-trainers');
    Route::get('/available-trainers/data', [MemberTrainerController::class, 'getAvailableTrainers'])->name('available-trainers.data');
    
    Route::get('/my-trainer', [MemberTrainerController::class, 'indexMyTrainer'])->name('my-trainer');
    Route::get('/member/trainer-stats/{trainerId}', [MemberTrainerController::class, 'getTrainerStats'])->name('member.trainer.stats');
    
    Route::get('/payment', [MemberPaymentController::class, 'index'])->name('payment');
    Route::get('/member/payments', [MemberPaymentController::class, 'getPayments'])->name('member.payments');
    Route::post('/member/payments', [MemberPaymentController::class, 'store'])->name('member.payments.store');
    Route::post('/member/payments/{id}/pay', [MemberPaymentController::class, 'pay'])->name('member.payments.pay');
    Route::delete('/member/payments/{id}/cancel', [MemberPaymentController::class, 'cancel'])->name('member.payments.cancel');
    
    // Profile Routes
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/my-profile', [MemberProfileController::class, 'index'])->name('profile');
    Route::get('/member/profile/data', [MemberProfileController::class, 'getData'])->name('member.profile.data');
    Route::put('/member/profile/update', [MemberProfileController::class, 'update'])->name('member.profile.update');
    
    Route::get('/member/payments/recent', [MemberPaymentController::class, 'getRecentPayments'])->name('member.payments.recent');
    
    // Member Membership API Routes
    Route::get('/member/membership/current', [MembershipController::class, 'getCurrent'])->name('member.membership.current');
    Route::get('/member/membership-plans', [MembershipController::class, 'getPlans'])->name('member.membership-plans');
    Route::post('/member/membership/select', [MembershipController::class, 'selectPlan'])->name('member.membership.select');
    Route::post('/member/membership/renew', [MembershipController::class, 'renew'])->name('member.membership.renew');
});

// Debug route to check schedule data structure
Route::get('/admin/debug-schedules', [DebugController::class, 'schedules']);

// ============ TRAINER ROUTES ============
Route::middleware(['auth'])->group(function () {
    // Trainer Views
    Route::get('/trainer/dashboard', [\App\Http\Controllers\Trainer\DashboardController::class, 'index'])->name('trainer.dashboard');
    Route::get('/trainer/my-schedule', [\App\Http\Controllers\Trainer\ScheduleController::class, 'index'])->name('trainer.my-schedule');
    Route::get('/trainer/my-clients', [\App\Http\Controllers\Trainer\ClientController::class, 'index'])->name('trainer.my-clients');
    Route::get('/trainer/payments', [\App\Http\Controllers\Trainer\PaymentController::class, 'index'])->name('trainer.payments');
    Route::get('/trainer/profile', [\App\Http\Controllers\Trainer\ProfileController::class, 'index'])->name('trainer.profile');
    
    // Trainer Schedule API Routes (for the blade template)
    Route::get('/trainer/schedules/data', [\App\Http\Controllers\Trainer\ScheduleController::class, 'getSchedules'])->name('trainer.schedules.data');
    Route::put('/trainer/schedules/{id}/status', [\App\Http\Controllers\Trainer\ScheduleController::class, 'updateStatus'])->name('trainer.schedules.update-status');
    Route::patch('/trainer/schedules/{id}/cancel', [\App\Http\Controllers\Trainer\ScheduleController::class, 'cancel'])->name('trainer.schedules.cancel');
    
    // Additional Trainer Schedule Routes (for other features)
    Route::get('/trainer/members/list', [\App\Http\Controllers\Trainer\ScheduleController::class, 'getMembers'])->name('trainer.members.list');
    Route::get('/trainer/trainers/list', [\App\Http\Controllers\Trainer\ScheduleController::class, 'getTrainers'])->name('trainer.trainers.list');
    Route::post('/trainer/schedules', [\App\Http\Controllers\Trainer\ScheduleController::class, 'store'])->name('trainer.schedules.store');
    Route::put('/trainer/schedules/{id}', [\App\Http\Controllers\Trainer\ScheduleController::class, 'update'])->name('trainer.schedules.update');
    
    // Trainer Dashboard API Routes
    Route::get('/trainer/stats', [\App\Http\Controllers\Trainer\DashboardController::class, 'getStats'])->name('trainer.stats');
    Route::get('/trainer/today-sessions', [\App\Http\Controllers\Trainer\DashboardController::class, 'getTodaySessions'])->name('trainer.today-sessions');
    Route::get('/trainer/active-clients', [\App\Http\Controllers\Trainer\DashboardController::class, 'getActiveClients'])->name('trainer.active-clients');
    Route::get('/trainer/weekly-sessions', [\App\Http\Controllers\Trainer\ScheduleController::class, 'getWeeklySessions'])->name('trainer.weekly-sessions');
    Route::get('/trainer/client-growth', [\App\Http\Controllers\Trainer\ScheduleController::class, 'getClientGrowth'])->name('trainer.client-growth');
    
    // Trainer Clients API Routes
    Route::get('/trainer/clients/data', [\App\Http\Controllers\Trainer\ClientController::class, 'getData'])->name('trainer.clients.data');
    Route::get('/trainer/clients/{id}', [\App\Http\Controllers\Trainer\ClientController::class, 'show'])->name('trainer.clients.show');
    
    // Trainer Payments API Routes
    Route::get('/trainer/payments/data', [\App\Http\Controllers\Trainer\PaymentController::class, 'getData'])->name('trainer.payments.data');
    Route::put('/trainer/payments/{id}/update', [\App\Http\Controllers\Trainer\PaymentController::class, 'update'])->name('trainer.payments.update');
    
    // Trainer Profile API Routes
    Route::get('/trainer/profile/data', [\App\Http\Controllers\Trainer\ProfileController::class, 'getData'])->name('trainer.profile.data');
    Route::put('/trainer/profile/update', [\App\Http\Controllers\Trainer\ProfileController::class, 'update'])->name('trainer.profile.update');
});

require __DIR__.'/auth.php';