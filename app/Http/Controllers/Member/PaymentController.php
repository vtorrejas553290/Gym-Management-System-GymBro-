<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('payment');
    }

    public function getPayments()
    {
        $payments = Payment::where('user_id', Auth::id())->get();
        return response()->json($payments);
    }

    public function getRecentPayments()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($payments);
    }

    public function store(Request $request)
    {
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
            'payment_id' => $this->generatePaymentId(),
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
    }

    public function pay(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'gcash_number' => 'required|string',
            'reference_number' => 'required|string',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,heic,heif|max:5120',
        ]);
        
        $updateData = [
            'gcash_number' => $validated['gcash_number'],
            'reference_number' => $validated['reference_number'],
        ];
        
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/payment_proofs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
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
    }

    public function cancel($id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'Pending' && !$payment->reference_number) {
            if ($payment->type === 'Trainer Session' && $payment->schedule_id) {
                $schedule = Schedule::find($payment->schedule_id);
                if ($schedule) {
                    $schedule->update(['status' => 'Cancelled']);
                }
            }
            
            $payment->delete();
            return response()->json(['success' => true, 'message' => 'Payment cancelled successfully']);
        }
        
        return response()->json(['error' => 'Cannot cancel this payment'], 400);
    }
}