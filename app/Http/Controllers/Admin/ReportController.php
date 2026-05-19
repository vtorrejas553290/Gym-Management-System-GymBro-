<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Trainer;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports');
    }

    /**
     * Get all report data
     */
    public function getReportData(Request $request)
    {
        $year = $request->year ?? date('Y');

        // =========================
        // MEMBERS
        // =========================
        $members = Member::whereYear('created_at', $year)->get();

        $previousMembers = Member::whereYear('created_at', $year - 1)->count();

        $memberGrowth = 0;

        if ($previousMembers > 0) {
            $memberGrowth = (($members->count() - $previousMembers) / $previousMembers) * 100;
        }

        // =========================
        // PAYMENTS / REVENUE
        // =========================
        $payments = Payment::where('status', 'Paid')
            ->whereYear('payment_date', $year)
            ->get();

        $totalRevenue = $payments->sum('amount');

        $previousRevenue = Payment::where('status', 'Paid')
            ->whereYear('payment_date', $year - 1)
            ->sum('amount');

        $revenueGrowth = 0;

        if ($previousRevenue > 0) {
            $revenueGrowth = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        }

        // =========================
        // MONTHLY REVENUE
        // =========================
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        $monthlyRevenue = [];

        foreach ($months as $index => $month) {

            $monthNumber = $index + 1;

            $revenue = Payment::where('status', 'Paid')
                ->whereYear('payment_date', $year)
                ->whereMonth('payment_date', $monthNumber)
                ->sum('amount');

            $monthlyRevenue[] = [
                'month' => $month,
                'revenue' => $revenue
            ];
        }

        // =========================
        // MEMBERSHIP TRENDS
        // =========================
        $membershipTrends = [];

        foreach ($months as $index => $month) {

            $monthNumber = $index + 1;

            $count = Member::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNumber)
                ->count();

            $membershipTrends[] = [
                'month' => $month,
                'members' => $count
            ];
        }

        // =========================
        // TRAINER POPULARITY
        // =========================
        $trainerStats = [];

        $trainers = Trainer::all();

        foreach ($trainers as $trainer) {

            $sessionCount = Schedule::where('trainerId', $trainer->id)
                ->whereYear('sessionDate', $year)
                ->count();

            $trainerStats[] = [
                'trainer' => $trainer->first_name . ' ' . $trainer->last_name,
                'sessions' => $sessionCount
            ];
        }

        // Sort descending
        usort($trainerStats, function ($a, $b) {
            return $b['sessions'] <=> $a['sessions'];
        });

        // =========================
        // RETENTION RATE
        // =========================
        $memberIdsWithSessions = Schedule::whereYear('sessionDate', $year)
            ->pluck('memberId')
            ->unique();

        $retentionRate = 0;

        if ($members->count() > 0) {
            $retentionRate = ($memberIdsWithSessions->count() / $members->count()) * 100;
        }

        // =========================
        // AVERAGE SESSION DURATION
        // =========================
        $schedules = Schedule::whereYear('sessionDate', $year)->get();

        $totalMinutes = 0;
        $sessionCount = 0;

        foreach ($schedules as $schedule) {

            $duration = $schedule->duration;

            if ($duration) {

                if (is_numeric($duration)) {

                    $minutes = (int) $duration;

                } else {

                    $duration = strtolower($duration);

                    if (str_contains($duration, 'hour')) {

                        $hours = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
                        $minutes = $hours * 60;

                    } elseif (str_contains($duration, 'min')) {

                        $minutes = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);

                    } else {

                        $minutes = (int) $duration;
                    }
                }

                if ($minutes > 0) {
                    $totalMinutes += $minutes;
                    $sessionCount++;
                }
            }
        }

        $averageSessionDuration = 0;

        if ($sessionCount > 0) {
            $averageSessionDuration = round($totalMinutes / $sessionCount);
        }

        // =========================
        // RETURN RESPONSE
        // =========================
        return response()->json([
            'year' => $year,

            'summary' => [
                'total_revenue' => $totalRevenue,
                'new_members' => $members->count(),
                'revenue_growth' => round($revenueGrowth, 1),
                'member_growth' => round($memberGrowth, 1),
                'retention_rate' => round($retentionRate, 1),
                'average_session_duration' => $averageSessionDuration,
            ],

            'monthly_revenue' => $monthlyRevenue,

            'membership_trends' => $membershipTrends,

            'trainer_statistics' => array_slice($trainerStats, 0, 6),

            'payments' => $payments,

            'members' => $members,

            'schedules' => $schedules,
        ]);
    }
}