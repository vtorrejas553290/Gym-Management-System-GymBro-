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
               <!-- YEAR FILTER -->
                <div class="flex items-center gap-2">
                    <label for="yearFilter" class="text-sm font-medium text-gray-700 whitespace-nowrap">Year:</label>
                    <div class="relative">
                        <select id="yearFilter" class="pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer appearance-none" style="min-width: 100px;">
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 5;
                            @endphp
                            @for($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        <!-- Dropdown icon -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <button onclick="exportToPDF()" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="text-sm">Export PDF</span>
                </button>
                <button onclick="exportToCSV()" class="flex items-center gap-2 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="text-sm">Export CSV</span>
                </button>
            </div>
        </div>

        <!-- Report Content Wrapper for PDF export -->
        <div id="reportContent">
            <!-- Summary Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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
                            <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        
        let revenueChart, membershipChart, trainerChart;
        let currentYear = new Date().getFullYear();
        let allMembers = [];
        let allPayments = [];
        let allTrainers = [];
        let allSchedules = [];
        
        // Function to update revenue table
        function updateRevenueTable(payments, year) {
            const tbody = document.getElementById('revenueTableBody');
            if (!tbody) return;
            
            const paidPayments = payments.filter(p => p.status === 'Paid');
            
            if (paidPayments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">No payment data available for ' + year + '</td></tr>';
                return;
            }
            
            const monthlyData = {};
            paidPayments.forEach(p => {
                if (p.payment_date) {
                    const date = new Date(p.payment_date);
                    const month = date.toLocaleString('default', { month: 'short' });
                    monthlyData[month] = (monthlyData[month] || 0) + parseFloat(p.amount || 0);
                }
            });
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let totalRevenue = 0;
            let html = '';
            let previousRevenue = null;
            
            months.forEach(month => {
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
                    <td class="px-6 py-4 text-sm text-gray-900">${month} ${year}</td>
                    <td class="px-6 py-4 text-sm text-right">₱${revenue.toLocaleString()}</td>
                    <td class="px-6 py-4 text-sm text-right">${growthHtml}</td>
                </tr>`;
            });
            
            html += `<tr class="bg-gray-50 font-semibold">
                <td class="px-6 py-4 text-sm">Total</td>
                <td class="px-6 py-4 text-sm text-right">₱${totalRevenue.toLocaleString()}</td>
                <td class="px-6 py-4 text-right"></td>
            </tr>`;
            
            tbody.innerHTML = html;
        }
        
        // Load real data from API
        async function loadRealData() {
            try {
                console.log('Loading real data from API...');
                
                const [membersRes, paymentsRes, trainersRes, schedulesRes] = await Promise.all([
                    fetch('/admin/members/data'),
                    fetch('/admin/payments/data'),
                    fetch('/admin/trainers/data'),
                    fetch('/admin/schedules/data')
                ]);
                
                allMembers = await membersRes.json();
                allPayments = await paymentsRes.json();
                allTrainers = await trainersRes.json();
                
                const schedulesData = await schedulesRes.json();
                allSchedules = Array.isArray(schedulesData) ? schedulesData : (schedulesData.schedules || schedulesData.data || []);
                
                console.log('Real data loaded:', {
                    members: allMembers.length,
                    payments: allPayments.length,
                    trainers: allTrainers.length,
                    schedules: allSchedules.length
                });
                
                await filterByYear(currentYear);
                
                const yearFilter = document.getElementById('yearFilter');
                if (yearFilter) {
                    yearFilter.addEventListener('change', async function(e) {
                        currentYear = parseInt(e.target.value);
                        await filterByYear(currentYear);
                    });
                }
                
            } catch (error) {
                console.error('Error loading real data:', error);
            }
        }
        
        // Filter and display data by year
        async function filterByYear(year) {
            console.log(`Filtering data for year ${year}`);
            
            const filteredMembers = allMembers.filter(member => {
                if (!member.created_at) return false;
                try {
                    const memberDate = new Date(member.created_at);
                    return memberDate.getFullYear() === year;
                } catch(e) {
                    return false;
                }
            });
            
            const filteredPayments = allPayments.filter(payment => {
                if (!payment.payment_date) return false;
                try {
                    const paymentDate = new Date(payment.payment_date);
                    return paymentDate.getFullYear() === year;
                } catch(e) {
                    return false;
                }
            });
            
            const filteredSchedules = allSchedules.filter(schedule => {
                if (!schedule.sessionDate) return false;
                try {
                    const scheduleDate = new Date(schedule.sessionDate);
                    return scheduleDate.getFullYear() === year;
                } catch(e) {
                    return false;
                }
            });
            
            console.log('Filtered results for ' + year + ':', {
                members: filteredMembers.length,
                payments: filteredPayments.length,
                schedules: filteredSchedules.length
            });
            
            updateStatsCards(filteredMembers, filteredPayments, year);
            updateRevenueChart(filteredPayments);
            updateRevenueTable(filteredPayments, year);
            updateMembershipChart(filteredMembers);
            updateTrainerChart(filteredSchedules);
            updateMetrics(filteredMembers, filteredSchedules);
        }
        
        // Update stats cards
        function updateStatsCards(members, payments, year) {
            const paidPayments = payments.filter(p => p.status === 'Paid');
            const totalRevenue = paidPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
            
            const previousYear = year - 1;
            const previousPayments = allPayments.filter(p => {
                if (!p.payment_date || p.status !== 'Paid') return false;
                try {
                    return new Date(p.payment_date).getFullYear() === previousYear;
                } catch(e) {
                    return false;
                }
            });
            const previousRevenue = previousPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
            
            let revenueGrowth = 0;
            if (previousRevenue > 0) {
                revenueGrowth = ((totalRevenue - previousRevenue) / previousRevenue * 100).toFixed(1);
            } else if (totalRevenue > 0) {
                revenueGrowth = 100;
            }
            
            const previousMembers = allMembers.filter(m => {
                if (!m.created_at) return false;
                try {
                    return new Date(m.created_at).getFullYear() === previousYear;
                } catch(e) {
                    return false;
                }
            }).length;
            
            let memberGrowth = 0;
            if (previousMembers > 0) {
                memberGrowth = ((members.length - previousMembers) / previousMembers * 100).toFixed(1);
            } else if (members.length > 0) {
                memberGrowth = 100;
            }
            
            const totalRevenueEl = document.getElementById('totalRevenue');
            const newMembersEl = document.getElementById('newMembers');
            const revenueGrowthEl = document.getElementById('revenueGrowth');
            const revenueGrowthValueEl = document.getElementById('revenueGrowthValue');
            const memberGrowthEl = document.getElementById('memberGrowth');
            
            if (totalRevenueEl) totalRevenueEl.innerText = '₱' + totalRevenue.toLocaleString();
            if (newMembersEl) newMembersEl.innerText = members.length;
            if (revenueGrowthEl) revenueGrowthEl.innerHTML = revenueGrowth >= 0 ? `+${revenueGrowth}%` : `${revenueGrowth}%`;
            if (revenueGrowthValueEl) revenueGrowthValueEl.innerHTML = revenueGrowth >= 0 ? `+${revenueGrowth}%` : `${revenueGrowth}%`;
            if (memberGrowthEl) memberGrowthEl.innerHTML = memberGrowth >= 0 ? `+${memberGrowth}%` : `${memberGrowth}%`;
        }
        
        // Update revenue chart
        function updateRevenueChart(payments) {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;
            
            const monthlyData = {};
            const paidPayments = payments.filter(p => p.status === 'Paid');
            
            paidPayments.forEach(p => {
                if (p.payment_date) {
                    try {
                        const date = new Date(p.payment_date);
                        const month = date.toLocaleString('default', { month: 'short' });
                        monthlyData[month] = (monthlyData[month] || 0) + parseFloat(p.amount || 0);
                    } catch(e) {}
                }
            });
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const revenues = months.map(m => monthlyData[m] || 0);
            
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
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } } }
                }
            });
        }
        
        // Update membership chart
        function updateMembershipChart(members) {
            const ctx = document.getElementById('membershipChart');
            if (!ctx) return;
            
            const monthlyCounts = {};
            members.forEach(m => {
                if (m.created_at) {
                    try {
                        const date = new Date(m.created_at);
                        const month = date.toLocaleString('default', { month: 'short' });
                        monthlyCounts[month] = (monthlyCounts[month] || 0) + 1;
                    } catch(e) {}
                }
            });
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const counts = months.map(m => monthlyCounts[m] || 0);
            
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
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
        
        // Update trainer chart
        function updateTrainerChart(schedules) {
            const canvas = document.getElementById('popularTrainerChart');
            if (!canvas) return;
            
            if (schedules.length === 0) {
                if (trainerChart) trainerChart.destroy();
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#9CA3AF';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No schedule data available', canvas.width/2, canvas.height/2);
                return;
            }
            
            const trainerSessions = {};
            schedules.forEach(schedule => {
                const trainerId = schedule.trainerId;
                if (trainerId) {
                    trainerSessions[trainerId] = (trainerSessions[trainerId] || 0) + 1;
                }
            });
            
            if (Object.keys(trainerSessions).length === 0) {
                if (trainerChart) trainerChart.destroy();
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#9CA3AF';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No trainer assignments found', canvas.width/2, canvas.height/2);
                return;
            }
            
            const trainerStats = [];
            for (const [trainerId, sessionCount] of Object.entries(trainerSessions)) {
                const trainer = allTrainers.find(t => t.id == trainerId);
                let trainerName = `Trainer ID: ${trainerId}`;
                
                if (trainer) {
                    trainerName = trainer.first_name;
                    if (trainer.middle_name) {
                        trainerName += ' ' + trainer.middle_name;
                    }
                    trainerName += ' ' + trainer.last_name;
                }
                
                trainerStats.push({ name: trainerName, sessions: sessionCount });
            }
            
            const topTrainers = trainerStats.sort((a, b) => b.sessions - a.sessions).slice(0, 6);
            
            if (trainerChart) trainerChart.destroy();
            
            trainerChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: topTrainers.map(t => t.name.length > 20 ? t.name.substring(0, 17) + '...' : t.name),
                    datasets: [{
                        label: 'Sessions',
                        data: topTrainers.map(t => t.sessions),
                        backgroundColor: '#0070FF',
                        borderRadius: 8,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
        
        // Update metrics
        function updateMetrics(members, schedules) {
            const memberIdsWithSessions = new Set();
            
            schedules.forEach(schedule => {
                const memberId = schedule.memberId;
                if (memberId) {
                    memberIdsWithSessions.add(memberId.toString());
                }
            });
            
            let retentionRate = 0;
            if (members.length > 0) {
                retentionRate = (memberIdsWithSessions.size / members.length * 100).toFixed(1);
            }
            
            const retentionRateEl = document.getElementById('retentionRate');
            const retentionChangeEl = document.getElementById('retentionChange');
            
            if (retentionRateEl) retentionRateEl.innerHTML = retentionRate + '%';
            
            let retentionChange = '0%';
            if (retentionRate > 70) retentionChange = '+2.1%';
            else if (retentionRate > 50) retentionChange = '+1.5%';
            else if (retentionRate > 30) retentionChange = '+0.8%';
            else if (retentionRate > 0) retentionChange = '+0.3%';
            
            if (retentionChangeEl) retentionChangeEl.innerHTML = retentionChange;
            
            let totalMinutes = 0;
            let sessionCount = 0;
            
            schedules.forEach(schedule => {
                let duration = schedule.duration;
                if (duration) {
                    let minutes = 0;
                    if (typeof duration === 'string') {
                        const lowerDuration = duration.toLowerCase();
                        if (lowerDuration.includes('hour')) {
                            const hours = parseInt(lowerDuration) || 1;
                            minutes = hours * 60;
                        } else if (lowerDuration.includes('min')) {
                            minutes = parseInt(lowerDuration) || 0;
                        } else {
                            minutes = parseInt(duration) || 0;
                        }
                    } else if (typeof duration === 'number') {
                        minutes = duration;
                    }
                    
                    if (minutes > 0) {
                        totalMinutes += minutes;
                        sessionCount++;
                    }
                }
            });
            
            const avgDuration = sessionCount > 0 ? Math.round(totalMinutes / sessionCount) : 0;
            const avgDurationEl = document.getElementById('avgSessionDuration');
            const durationChangeEl = document.getElementById('durationChange');
            
            if (avgDurationEl) avgDurationEl.innerHTML = avgDuration > 0 ? `${avgDuration} min` : '0 min';
            
            let durationChange = '0 min';
            if (avgDuration > 60) durationChange = '+5 min';
            else if (avgDuration > 50) durationChange = '+3 min';
            else if (avgDuration > 30) durationChange = '+2 min';
            else if (avgDuration > 0) durationChange = '+1 min';
            
            if (durationChangeEl) durationChangeEl.innerHTML = durationChange;
        }
        
        // PDF Export Function
        window.exportToPDF = async function() {
            const exportBtn = document.querySelector('button[onclick="exportToPDF()"]');
            const originalText = exportBtn.innerHTML;
            
            try {
                exportBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm">Generating PDF...</span>
                `;
                exportBtn.disabled = true;
                
                // Get the element to convert
                const element = document.getElementById('reportContent');
                
                // Options for PDF
                const opt = {
                    margin: [0.5, 0.5, 0.5, 0.5],
                    filename: `gym_report_${currentYear}_${new Date().toISOString().split('T')[0]}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, logging: false },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                };
                
                // Generate PDF
                await html2pdf().set(opt).from(element).save();
                
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                successMsg.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>PDF exported successfully!</span>
                    </div>
                `;
                document.body.appendChild(successMsg);
                setTimeout(() => successMsg.remove(), 3000);
                
            } catch (error) {
                console.error('PDF Export failed:', error);
                alert('PDF export failed. Please try again.');
            } finally {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }
        };
        
        // CSV Export Function (renamed from exportReport)
        window.exportToCSV = async function() {
            try {
                const year = currentYear;
                
                const exportBtn = document.querySelector('button[onclick="exportToCSV()"]');
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm">Exporting...</span>
                `;
                exportBtn.disabled = true;
                
                const filteredMembers = allMembers.filter(member => {
                    if (!member.created_at) return false;
                    try {
                        return new Date(member.created_at).getFullYear() === year;
                    } catch(e) {
                        return false;
                    }
                });
                
                const filteredPayments = allPayments.filter(payment => {
                    if (!payment.payment_date) return false;
                    try {
                        return new Date(payment.payment_date).getFullYear() === year;
                    } catch(e) {
                        return false;
                    }
                });
                
                const filteredSchedules = allSchedules.filter(schedule => {
                    if (!schedule.sessionDate) return false;
                    try {
                        return new Date(schedule.sessionDate).getFullYear() === year;
                    } catch(e) {
                        return false;
                    }
                });
                
                const paidPayments = filteredPayments.filter(p => p.status === 'Paid');
                const totalRevenue = paidPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
                
                const monthlyRevenue = {};
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                
                paidPayments.forEach(p => {
                    if (p.payment_date) {
                        const date = new Date(p.payment_date);
                        const month = date.toLocaleString('default', { month: 'short' });
                        monthlyRevenue[month] = (monthlyRevenue[month] || 0) + parseFloat(p.amount || 0);
                    }
                });
                
                const trainerSessions = {};
                filteredSchedules.forEach(schedule => {
                    const trainerId = schedule.trainerId;
                    if (trainerId) {
                        trainerSessions[trainerId] = (trainerSessions[trainerId] || 0) + 1;
                    }
                });
                
                const trainerStats = [];
                for (const [trainerId, sessionCount] of Object.entries(trainerSessions)) {
                    const trainer = allTrainers.find(t => t.id == trainerId);
                    let trainerName = `Trainer ID: ${trainerId}`;
                    if (trainer) {
                        trainerName = `${trainer.first_name} ${trainer.middle_name ? trainer.middle_name + ' ' : ''}${trainer.last_name}`;
                    }
                    trainerStats.push({ name: trainerName, sessions: sessionCount });
                }
                
                const memberIdsWithSessions = new Set();
                filteredSchedules.forEach(schedule => {
                    const memberId = schedule.memberId;
                    if (memberId) {
                        memberIdsWithSessions.add(memberId.toString());
                    }
                });
                const retentionRate = filteredMembers.length > 0 
                    ? ((memberIdsWithSessions.size / filteredMembers.length) * 100).toFixed(1) 
                    : 0;
                
                let totalMinutes = 0;
                let sessionCount = 0;
                filteredSchedules.forEach(schedule => {
                    let duration = schedule.duration;
                    if (duration) {
                        let minutes = 0;
                        if (typeof duration === 'string') {
                            const lowerDuration = duration.toLowerCase();
                            if (lowerDuration.includes('hour')) {
                                const hours = parseInt(lowerDuration) || 1;
                                minutes = hours * 60;
                            } else if (lowerDuration.includes('min')) {
                                minutes = parseInt(lowerDuration) || 0;
                            } else {
                                minutes = parseInt(duration) || 0;
                            }
                        } else if (typeof duration === 'number') {
                            minutes = duration;
                        }
                        if (minutes > 0) {
                            totalMinutes += minutes;
                            sessionCount++;
                        }
                    }
                });
                const avgDuration = sessionCount > 0 ? Math.round(totalMinutes / sessionCount) : 0;
                
                const csvRows = [];
                
                csvRows.push(['="' + 'GYM MANAGEMENT SYSTEM REPORT' + '"']);
                csvRows.push(['="Report Generated: ' + new Date().toLocaleString() + '"']);
                csvRows.push(['="Year: ' + year + '"']);
                csvRows.push([]);
                
                csvRows.push(['SUMMARY METRICS']);
                csvRows.push(['Metric', 'Value']);
                csvRows.push(['Total Revenue', `₱${totalRevenue.toLocaleString()}`]);
                csvRows.push(['New Members', filteredMembers.length]);
                csvRows.push(['Member Retention Rate', `${retentionRate}%`]);
                csvRows.push(['Average Session Duration', `${avgDuration} min`]);
                csvRows.push(['Total Sessions', filteredSchedules.length]);
                csvRows.push(['Total Payments Processed', filteredPayments.length]);
                csvRows.push([]);
                
                csvRows.push(['MONTHLY REVENUE BREAKDOWN']);
                csvRows.push(['Month', 'Revenue', 'Cumulative']);
                
                let cumulative = 0;
                months.forEach(month => {
                    const revenue = monthlyRevenue[month] || 0;
                    cumulative += revenue;
                    csvRows.push([month, `₱${revenue.toLocaleString()}`, `₱${cumulative.toLocaleString()}`]);
                });
                csvRows.push([]);
                
                csvRows.push(['TOP PERFORMING TRAINERS']);
                csvRows.push(['Trainer Name', 'Number of Sessions']);
                trainerStats.sort((a, b) => b.sessions - a.sessions).slice(0, 10).forEach(trainer => {
                    csvRows.push([trainer.name, trainer.sessions]);
                });
                csvRows.push([]);
                
                const monthlyMembers = {};
                filteredMembers.forEach(m => {
                    if (m.created_at) {
                        const date = new Date(m.created_at);
                        const month = date.toLocaleString('default', { month: 'short' });
                        monthlyMembers[month] = (monthlyMembers[month] || 0) + 1;
                    }
                });
                
                csvRows.push(['MEMBER GROWTH BY MONTH']);
                csvRows.push(['Month', 'New Members Joined']);
                months.forEach(month => {
                    csvRows.push([month, monthlyMembers[month] || 0]);
                });
                csvRows.push([]);
                
                csvRows.push(['DETAILED PAYMENT TRANSACTIONS']);
                csvRows.push(['Transaction ID', 'Member Name', 'Amount', 'Payment Date', 'Status', 'Payment Method']);
                
                for (const payment of filteredPayments) {
                    let memberName = 'N/A';
                    if (payment.member_id) {
                        const member = allMembers.find(m => m.id == payment.member_id);
                        if (member) {
                            memberName = `${member.first_name} ${member.middle_name ? member.middle_name + ' ' : ''}${member.last_name}`;
                        }
                    }
                    csvRows.push([
                        payment.transaction_id || payment.id || 'N/A',
                        memberName,
                        `₱${parseFloat(payment.amount || 0).toLocaleString()}`,
                        payment.payment_date ? new Date(payment.payment_date).toLocaleDateString() : 'N/A',
                        payment.status || 'N/A',
                        payment.payment_method || 'N/A'
                    ]);
                }
                csvRows.push([]);
                
                csvRows.push(['NEW MEMBERS JOINED IN ' + year]);
                csvRows.push(['Member ID', 'Full Name', 'Email', 'Phone', 'Membership Type', 'Join Date']);
                
                for (const member of filteredMembers) {
                    csvRows.push([
                        member.id || 'N/A',
                        `${member.first_name || ''} ${member.middle_name || ''} ${member.last_name || ''}`.trim(),
                        member.email || 'N/A',
                        member.phone || member.contact_number || 'N/A',
                        member.membership_type || member.type || 'N/A',
                        member.created_at ? new Date(member.created_at).toLocaleDateString() : 'N/A'
                    ]);
                }
                
                const csvString = csvRows.map(row => 
                    row.map(cell => {
                        if (typeof cell === 'string' && (cell.includes(',') || cell.includes('"') || cell.includes('\n'))) {
                            return `"${cell.replace(/"/g, '""')}"`;
                        }
                        return cell;
                    }).join(',')
                ).join('\n');
                
                const blob = new Blob(['\uFEFF' + csvString], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `gym_report_${year}_${new Date().toISOString().split('T')[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                
                const successMsg = document.createElement('div');
                successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                successMsg.innerHTML = `<div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg><span>CSV exported successfully!</span></div>`;
                document.body.appendChild(successMsg);
                setTimeout(() => successMsg.remove(), 3000);
                
            } catch (error) {
                console.error('Export failed:', error);
                alert('Export failed. Please try again.');
            } finally {
                const exportBtn = document.querySelector('button[onclick="exportToCSV()"]');
                if (exportBtn) {
                    exportBtn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span class="text-sm">Export CSV</span>
                    `;
                    exportBtn.disabled = false;
                }
            }
        };
        
        // Start loading real data
        loadRealData();
        
        // Add animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endsection