<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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
        return view('trainer.payments');
    }

    public function getData()
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json([]);
        }
        
        $scheduleIds = Schedule::where('trainer_id', $trainerId)->pluck('id');
        
        $payments = Payment::whereIn('schedule_id', $scheduleIds)
            ->where('type', 'Trainer Session')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($payments->isEmpty()) {
            $memberIds = Schedule::where('trainer_id', $trainerId)
                ->distinct('member_id')
                ->pluck('member_id');
            
            $payments = Payment::whereIn('user_id', $memberIds)
                ->where('type', 'Trainer Session')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
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
                'proof_image' => $payment->proof_image,
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
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }
        
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
        
        if ($validated['status'] === 'Paid' && $oldStatus !== 'Paid') {
            if ($schedule) {
                $schedule->update(['payment_status' => 'Paid']);
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Payment updated successfully']);
    }
}