<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFExportController extends Controller
{
    public function exportPDF($year)
    {
        // Get your data (adjust these models to match your actual model names)
        $members = \App\Models\Member::whereYear('created_at', $year)->get();
        $payments = \App\Models\Payment::whereYear('payment_date', $year)->where('status', 'Paid')->get();
        $schedules = \App\Models\Schedule::whereYear('sessionDate', $year)->get();
        $trainers = \App\Models\Trainer::all();
        
        // Calculate totals
        $totalRevenue = $payments->sum('amount');
        $newMembers = $members->count();
        
        // Monthly revenue
        $monthlyRevenue = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        foreach ($months as $index => $month) {
            $monthNum = $index + 1;
            $monthlyRevenue[$month] = $payments->filter(function($payment) use ($monthNum) {
                return $payment->payment_date && date('n', strtotime($payment->payment_date)) == $monthNum;
            })->sum('amount');
        }
        
        // Prepare data for view
        $data = [
            'year' => $year,
            'totalRevenue' => $totalRevenue,
            'newMembers' => $newMembers,
            'monthlyRevenue' => $monthlyRevenue,
            'payments' => $payments,
            'members' => $members,
            'generatedAt' => now()->format('F d, Y h:i A')
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf-report', $data);
        return $pdf->download("gym_report_{$year}.pdf");
    }
}