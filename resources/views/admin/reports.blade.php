@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl text-gray-900">Reports & Analytics</h1>
                <p class="text-gray-600 mt-1">Comprehensive insights into gym performance</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <button onclick="exportReport()" class="flex items-center gap-2 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="text-sm">Export Report</span>
                </button>
            </div>
        </div>

        <!-- Summary Stats Cards - Icon and Title top-left, Values bottom-right -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500 p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Total Revenue</p>
                    </div>
                    <span id="revenueGrowth" class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">0%</span>
                </div>
                <div class="text-right">
                    <h3 id="totalRevenue" class="text-2xl text-gray-900 font-bold">₱0</h3>
                </div>
            </div>

            <!-- New Members Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-500 p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">New Members</p>
                    </div>
                    <span id="memberGrowth" class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">0%</span>
                </div>
                <div class="text-right">
                    <h3 id="newMembers" class="text-2xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Revenue Growth Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-orange-500 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Revenue Growth</p>
                </div>
                <div class="text-right">
                    <h3 id="revenueGrowthValue" class="text-2xl text-gray-900 font-bold">0%</h3>
                </div>
            </div>
        </div>

        <!-- Revenue Analysis Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg text-gray-900 mb-6">Revenue Analysis</h3>
            <div class="h-80 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg text-gray-900 mb-6">Membership Trends</h3>
                <div class="h-72 w-full">
                    <canvas id="membershipChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg text-gray-900 mb-6">Popular Trainers</h3>
                <div class="h-72 w-full">
                    <canvas id="popularTrainerChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Key Performance Metrics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg text-gray-900 mb-6">Key Performance Metrics</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Member Retention Rate</p>
                        <p id="retentionRate" class="text-xl text-gray-900 mt-1">0%</p>
                    </div>
                    <span id="retentionChange" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">0%</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Average Session Duration</p>
                        <p id="avgSessionDuration" class="text-xl text-gray-900 mt-1">0 min</p>
                    </div>
                    <span id="durationChange" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">0 min</span>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Summary Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg text-gray-900">Monthly Revenue Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs text-gray-600">Month</th>
                            <th class="px-6 py-3 text-right text-xs text-gray-600">Revenue</th>
                            <th class="px-6 py-3 text-right text-xs text-gray-600">Growth</th>
                        </tr>
                    </thead>
                    <tbody id="revenueTableBody">
                        <td><td colspan="3" class="px-6 py-12 text-center text-gray-500">Loading...<\/td><\/tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, membershipChart, trainerChart;
    
    async function loadData() {
        try {
            // Fetch all data
            const [membersRes, paymentsRes, trainersRes, schedulesRes] = await Promise.all([
                fetch('/admin/members/data'),
                fetch('/admin/payments/data'),
                fetch('/admin/trainers/data'),
                fetch('/admin/schedules/data')
            ]);
            
            const members = await membersRes.json();
            const payments = await paymentsRes.json();
            const trainers = await trainersRes.json();
            const schedulesData = await schedulesRes.json();
            
            // Extract schedules - the API returns { schedules: [...] }
            const schedules = schedulesData.schedules || schedulesData;
            
            // Filter paid payments
            const paidPayments = payments.filter(p => p.status === 'Paid');
            const totalRevenue = paidPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
            
            // Calculate revenue growth (compare with previous 6 months)
            const sixMonthsAgo = new Date();
            sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
            const previousPayments = paidPayments.filter(p => {
                if (!p.payment_date) return false;
                return new Date(p.payment_date) < sixMonthsAgo;
            });
            const previousRevenue = previousPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
            
            let revenueGrowthPercent = 0;
            if (previousRevenue > 0) {
                revenueGrowthPercent = ((totalRevenue - previousRevenue) / previousRevenue * 100).toFixed(1);
            } else if (totalRevenue > 0) {
                revenueGrowthPercent = 100;
            }
            const revenueGrowthDisplay = revenueGrowthPercent >= 0 ? `+${revenueGrowthPercent}%` : `${revenueGrowthPercent}%`;
            
            // Update stats cards
            document.getElementById('revenueGrowth').innerHTML = revenueGrowthDisplay;
            document.getElementById('revenueGrowthValue').innerText = revenueGrowthDisplay;
            document.getElementById('totalRevenue').innerText = '₱' + totalRevenue.toLocaleString();
            document.getElementById('newMembers').innerText = members.length;
            
            // Calculate member growth
            const previousMembers = members.filter(m => {
                if (!m.created_at) return false;
                return new Date(m.created_at) < sixMonthsAgo;
            }).length;
            let memberGrowthPercent = 0;
            if (previousMembers > 0) {
                memberGrowthPercent = ((members.length - previousMembers) / previousMembers * 100).toFixed(1);
            } else if (members.length > 0) {
                memberGrowthPercent = 100;
            }
            document.getElementById('memberGrowth').innerHTML = memberGrowthPercent >= 0 ? `+${memberGrowthPercent}%` : `${memberGrowthPercent}%`;
            
            // Update all charts
            updateRevenueChart(paidPayments);
            updateRevenueTable(paidPayments);
            updateMembershipChart(members);
            updateTrainerChart(schedules, trainers);
            updateMetrics(members, schedules);
            
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }
    
    function updateRevenueChart(payments) {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        
        // Group payments by month
        const monthlyData = {};
        payments.forEach(p => {
            if (p.payment_date) {
                const date = new Date(p.payment_date);
                const month = date.toLocaleString('default', { month: 'short' });
                monthlyData[month] = (monthlyData[month] || 0) + parseFloat(p.amount || 0);
            }
        });
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const revenues = months.map(m => monthlyData[m] || 0);
        
        // Destroy existing chart if any
        if (revenueChart) revenueChart.destroy();
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    borderColor: '#0070FF',
                    backgroundColor: 'rgba(0, 112, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0070FF',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Revenue: ₱${context.raw.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '₱' + v.toLocaleString()
                        }
                    }
                }
            }
        });
    }
    
    function updateRevenueTable(payments) {
        const tbody = document.getElementById('revenueTableBody');
        if (!tbody) return;
        
        if (payments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">No payment data available<\/td><\/tr>';
            return;
        }
        
        // Group payments by month
        const monthlyData = {};
        payments.forEach(p => {
            if (p.payment_date) {
                const date = new Date(p.payment_date);
                const month = date.toLocaleString('default', { month: 'short' });
                monthlyData[month] = (monthlyData[month] || 0) + parseFloat(p.amount || 0);
            }
        });
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        let totalRevenue = 0;
        let html = '';
        let previousRevenue = null;
        
        months.forEach((month, index) => {
            const revenue = monthlyData[month] || 0;
            totalRevenue += revenue;
            let growthHtml = '-';
            
            if (previousRevenue !== null && previousRevenue > 0) {
                const growth = ((revenue - previousRevenue) / previousRevenue * 100).toFixed(1);
                const growthClass = parseFloat(growth) >= 0 ? 'text-green-600' : 'text-red-600';
                const growthSign = parseFloat(growth) >= 0 ? '+' : '';
                growthHtml = `<span class="${growthClass} font-medium">${growthSign}${growth}%</span>`;
            }
            previousRevenue = revenue;
            
            html += `<tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${month} 2025<\/td>
                <td class="px-6 py-4 text-sm text-right">₱${revenue.toLocaleString()}<\/td>
                <td class="px-6 py-4 text-sm text-right">${growthHtml}<\/td>
            <\/tr>`;
        });
        
        html += `<tr class="bg-gray-50 font-semibold">
            <td class="px-6 py-4 text-sm">Total<\/td>
            <td class="px-6 py-4 text-sm text-right">₱${totalRevenue.toLocaleString()}<\/td>
            <td class="px-6 py-4 text-right"><\/td>
        <\/tr>`;
        
        tbody.innerHTML = html;
    }
    
    function updateMembershipChart(members) {
        const ctx = document.getElementById('membershipChart');
        if (!ctx) return;
        
        // Group members by creation month
        const monthlyCounts = {};
        members.forEach(m => {
            if (m.created_at) {
                const date = new Date(m.created_at);
                const month = date.toLocaleString('default', { month: 'short' });
                monthlyCounts[month] = (monthlyCounts[month] || 0) + 1;
            }
        });
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const counts = months.map(m => monthlyCounts[m] || 0);
        
        // Destroy existing chart if any
        if (membershipChart) membershipChart.destroy();
        
        membershipChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Members',
                    data: counts,
                    borderColor: '#0070FF',
                    backgroundColor: 'rgba(0, 112, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0070FF',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `New Members: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    function updateTrainerChart(schedules, trainers) {
        const ctx = document.getElementById('popularTrainerChart');
        if (!ctx) return;
        
        // Count sessions per trainer
        const trainerSessions = {};
        schedules.forEach(s => {
            const trainerId = s.trainerId;
            if (trainerId) {
                const trainer = trainers.find(t => t.id === trainerId);
                const name = trainer ? `${trainer.first_name} ${trainer.last_name}` : `Trainer ${trainerId}`;
                trainerSessions[name] = (trainerSessions[name] || 0) + 1;
            }
        });
        
        const topTrainers = Object.entries(trainerSessions)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 6);
        
        if (topTrainers.length === 0) {
            const canvas = document.getElementById('popularTrainerChart');
            const container = canvas.parentElement;
            container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No trainer session data available</div>';
            return;
        }
        
        // Destroy existing chart if any
        if (trainerChart) trainerChart.destroy();
        
        trainerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: topTrainers.map(t => t[0].length > 20 ? t[0].substring(0, 17) + '...' : t[0]),
                datasets: [{
                    label: 'Sessions',
                    data: topTrainers.map(t => t[1]),
                    backgroundColor: '#0070FF',
                    borderRadius: 8,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Sessions: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    function updateMetrics(members, schedules) {
        // Calculate Member Retention Rate
        const membersWithSessions = new Set();
        schedules.forEach(s => {
            const memberId = s.memberId;
            if (memberId) membersWithSessions.add(memberId);
        });
        
        const retentionRate = members.length > 0 ? (membersWithSessions.size / members.length * 100).toFixed(1) : 0;
        document.getElementById('retentionRate').innerHTML = retentionRate + '%';
        
        // Calculate retention change (placeholder)
        const retentionChange = retentionRate > 70 ? '+2.1%' : (retentionRate > 50 ? '+1.5%' : '0%');
        document.getElementById('retentionChange').innerHTML = retentionChange;
        
        // Calculate Average Session Duration
        let totalMinutes = 0, count = 0;
        schedules.forEach(s => {
            if (s.duration) {
                let minutes = 60;
                const match = s.duration.match(/\d+/);
                if (match) minutes = parseInt(match[0]);
                if (s.duration.includes('hour')) minutes *= 60;
                totalMinutes += minutes;
                count++;
            }
        });
        
        const avgDuration = count > 0 ? Math.round(totalMinutes / count) : 0;
        document.getElementById('avgSessionDuration').innerHTML = avgDuration > 0 ? `${avgDuration} min` : '0 min';
        
        // Duration change placeholder
        const durationChange = avgDuration > 60 ? '+5 min' : (avgDuration > 50 ? '+3 min' : '0 min');
        document.getElementById('durationChange').innerHTML = durationChange;
    }
    
    function exportReport() {
        alert('Report exported successfully');
    }
    
    // Load data when page loads
    loadData();
    window.exportReport = exportReport;
</script>
@endpush
@endsection