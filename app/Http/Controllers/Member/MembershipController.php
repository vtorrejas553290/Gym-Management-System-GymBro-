<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
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
        return view('membership');
    }

    public function getCurrent()
    {
        $user = Auth::user();
        
        $hasPaidMembership = Payment::where('user_id', $user->id)
            ->where('type', 'Membership')
            ->where('status', 'Paid')
            ->exists();
        
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
    }

    public function getPlans()
    {
        $plans = MembershipPlan::where('active', true)->get();
        return response()->json($plans);
    }

    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:membership_plans,id',
        ]);
        
        $plan = MembershipPlan::findOrFail($request->plan_id);
        $user = Auth::user();
        
        Payment::create([
            'payment_id' => $this->generatePaymentId(),
            'user_id' => $user->id,
            'type' => 'Membership',
            'amount' => $plan->price,
            'payment_date' => now(),
            'method' => 'GCash',
            'status' => 'Pending',
            'details' => "{$plan->name} Membership Plan - {$plan->duration}",
        ]);
        
        return response()->json(['success' => true, 'message' => 'Plan selected successfully. Payment pending admin approval.']);
    }

    public function renew()
    {
        $user = Auth::user();
        $plan = MembershipPlan::where('name', $user->plan)->first();
        
        if ($plan) {
            Payment::create([
                'payment_id' => $this->generatePaymentId(),
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
    }
}