<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private function generatePaymentId()
    {
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->payment_id, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        return 'PAY' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        return view('admin.payments');
    }

    public function getData()
    {
        $payments = Payment::with('user')->get();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
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
            'payment_id' => $this->generatePaymentId(),
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'method' => $validated['method'],
            'status' => $validated['status'],
            'details' => $validated['details'],
        ]);
        
        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
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
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $request->validate([
            'status' => 'required|in:Paid,Pending,Overdue',
        ]);
        $payment->update(['status' => $request->status]);
        return response()->json($payment);
    }

    public function updateStatusWithMessage(Request $request, $id)
    {
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
        
        if ($validated['status'] === 'Paid' && $oldStatus !== 'Paid') {
            if ($payment->schedule_id) {
                $schedule = Schedule::find($payment->schedule_id);
                if ($schedule) {
                    $schedule->update(['payment_status' => 'Paid']);
                }
            } else if ($payment->type === 'Trainer Session') {
                $schedule = Schedule::where('member_id', $payment->user_id)
                    ->where('payment_status', 'Pending')
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($schedule) {
                    $schedule->update(['payment_status' => 'Paid']);
                    $payment->update(['schedule_id' => $schedule->id]);
                }
            }
            
            if ($payment->type === 'Membership' || $payment->type === 'Membership Renewal') {
                $user = User::find($payment->user_id);
                if ($user) {
                    $planName = null;
                    if ($payment->details) {
                        if (preg_match('/^(\w+)\s+Membership/', $payment->details, $matches)) {
                            $planName = $matches[1];
                        }
                    }
                    
                    if ($planName) {
                        $user->update(['plan' => $planName]);
                    } else {
                        $user->update(['plan' => $user->plan ?? 'Basic']);
                    }
                    
                    if ($user->status !== 'Active') {
                        $user->update(['status' => 'Active']);
                    }
                }
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Payment updated successfully']);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(null, 204);
    }
}