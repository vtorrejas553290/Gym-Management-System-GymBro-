@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Admin Overview Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl text-gray-900">Welcome, {{ Auth::guard('admin')->user()->first_name }}</h1>
                <p class="text-gray-600 mt-1" id="currentDateTime"></p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg self-start sm:self-auto">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-green-800 font-medium">System Active</span>
            </div>
        </div>

        <!-- Key Performance Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Members Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#0070FF] p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Total Members</p>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <h3 id="totalMembers" class="text-2xl text-gray-900 font-bold">0</h3>
                    <span id="memberGrowth" class="text-xs text-green-600">Loading...</span>
                </div>
            </div>

            <!-- Active Trainers Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#FF9800] p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Active Trainers</p>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <h3 id="activeTrainers" class="text-2xl text-gray-900 font-bold">0</h3>
                    <span id="trainerGrowth" class="text-xs text-green-600">Loading...</span>
                </div>
            </div>

            <!-- Pending Payments Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#F44336] p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Pending Payments</p>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <h3 id="pendingPayments" class="text-2xl text-gray-900 font-bold">0</h3>
                    <span id="paymentCount" class="text-xs text-gray-500">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Analytics Overview Section - Membership Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg text-gray-900 font-semibold mb-1">Membership Distribution</h3>
            <p class="text-sm text-gray-600 mb-6">Active plans breakdown</p>
            <div id="membershipDistribution">
                <div class="space-y-4" id="distributionContent">
                    <div class="text-center py-8 text-gray-500">Loading membership data...</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions, Recent Activity, and Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h3 class="text-lg text-gray-900 font-semibold">Quick Actions</h3>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.members') }}" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 hover:border-[#0070FF]">
                        <div class="bg-[#0070FF] p-2 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-900">Manage Members</span>
                    </a>
                    <a href="{{ route('admin.payments') }}" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 hover:border-[#0070FF]">
                        <div class="bg-[#00BFA5] p-2 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-900">View Payments</span>
                    </a>
                    <a href="{{ route('admin.trainers') }}" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 hover:border-[#0070FF]">
                        <div class="bg-[#9C27B0] p-2 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-900">Manage Trainers</span>
                    </a>
                    <a href="{{ route('admin.membership-plans') }}" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 hover:border-[#0070FF]">
                        <div class="bg-[#FF9800] p-2 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-900">Membership Plans</span>
                    </a>
                    <a href="{{ route('admin.trainer-schedule') }}" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 hover:border-[#0070FF]">
                        <div class="bg-[#2196F3] p-2 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-900">Manage Schedules</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg text-gray-900 font-semibold">Recent Activities</h3>
                </div>
                <div id="recentActivities" class="space-y-4 max-h-[400px] overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">Loading activities...</div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg text-gray-900 font-semibold">Alerts & Notifications</h3>
                </div>
                <div id="alertsList" class="space-y-3 max-h-[400px] overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">Loading alerts...</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set current date and time
    const now = new Date();
    const dateTimeElement = document.getElementById('currentDateTime');
    if (dateTimeElement) {
        dateTimeElement.innerHTML = `${now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} • ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
    }

    // Load dashboard data from API
    async function loadDashboardData() {
        try {
            const [membersRes, trainersRes, paymentsRes, plansRes] = await Promise.all([
                fetch('/admin/members/data'),
                fetch('/admin/trainers/data'),
                fetch('/admin/payments/data'),
                fetch('/admin/membership-plans/data')
            ]);

            const members = await membersRes.json();
            const trainers = await trainersRes.json();
            const payments = await paymentsRes.json();
            const plans = await plansRes.json();

            updateStats(members, trainers, payments);
            updateMembershipDistribution(members, plans);
            updateRecentActivities(members, trainers, payments);
            updateAlerts(payments, members);
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    function updateStats(members, trainers, payments) {
        // Total Members
        const totalMembers = members.length;
        document.getElementById('totalMembers').innerText = totalMembers;
        
        const now = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(now.getDate() - 30);
        
        const recentMembers = members.filter(m => new Date(m.created_at) >= thirtyDaysAgo).length;
        document.getElementById('memberGrowth').innerHTML = `+${recentMembers}`;

        // Active Trainers
        const activeTrainers = trainers.filter(t => t.status === 'Active').length;
        document.getElementById('activeTrainers').innerText = activeTrainers;
        
        const trainerGrowth = trainers.filter(t => t.status === 'Active' && new Date(t.created_at) >= thirtyDaysAgo).length;
        document.getElementById('trainerGrowth').innerHTML = `+${trainerGrowth}`;

        // Pending Payments
        const pendingPayments = payments.filter(p => p.status === 'Pending').length;
        document.getElementById('pendingPayments').innerText = pendingPayments;
        document.getElementById('paymentCount').innerHTML = `${pendingPayments}`;
    }

    function updateMembershipDistribution(members, plans) {
        const planCounts = {};
        members.forEach(member => {
            const planName = member.plan || 'Basic';
            planCounts[planName] = (planCounts[planName] || 0) + 1;
        });

        const planColors = {
            'Basic': '#0070FF',
            'Premium': '#00BFA5',
            'VIP': '#9C27B0',
            'Annual': '#FF9800'
        };

        const total = members.length;
        const container = document.getElementById('distributionContent');
        
        if (total === 0) {
            container.innerHTML = '<div class="text-center py-8 text-gray-500">No members found</div>';
            return;
        }

        let distributionHtml = '<div class="space-y-4">';
        
        for (const [plan, count] of Object.entries(planCounts)) {
            const percentage = ((count / total) * 100).toFixed(1);
            const color = planColors[plan] || '#0070FF';
            distributionHtml += `
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">${escapeHtml(plan)}</span>
                        <span class="text-gray-900 font-medium">${count} (${percentage}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: ${percentage}%; background-color: ${color}"></div>
                    </div>
                </div>
            `;
        }
        
        distributionHtml += '</div>';
        
        distributionHtml += `
            <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-gray-200">
                ${Object.entries(planCounts).map(([plan, count]) => `
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: ${planColors[plan] || '#0070FF'}"></div>
                        <div>
                            <p class="text-xs text-gray-600">${escapeHtml(plan)}</p>
                            <p class="text-sm text-gray-900 font-medium">${count}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        container.innerHTML = distributionHtml;
    }

    function updateRecentActivities(members, trainers, payments) {
        const activities = [];
        
        const recentMembers = [...members].sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 3);
        recentMembers.forEach(member => {
            activities.push({
                action: "New member registered",
                user: `${member.first_name} ${member.last_name}`,
                time: formatRelativeTime(member.created_at),
                type: "success"
            });
        });
        
        const recentPayments = [...payments].sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 3);
        recentPayments.forEach(payment => {
            activities.push({
                action: `Payment received - ${payment.type}`,
                user: `₱${parseFloat(payment.amount).toLocaleString()}`,
                time: formatRelativeTime(payment.created_at),
                type: "success"
            });
        });
        
        activities.sort((a, b) => {
            const timeA = parseRelativeTime(a.time);
            const timeB = parseRelativeTime(b.time);
            return timeA - timeB;
        });
        
        const recentActivities = activities.slice(0, 6);
        
        const container = document.getElementById('recentActivities');
        if (recentActivities.length === 0) {
            container.innerHTML = '<div class="text-center py-8 text-gray-500">No recent activities</div>';
            return;
        }
        
        container.innerHTML = recentActivities.map(activity => `
            <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0">
                <div class="p-1 rounded-full ${activity.type === 'success' ? 'bg-green-100' : 'bg-blue-100'}">
                    <div class="w-2 h-2 rounded-full ${activity.type === 'success' ? 'bg-green-500' : 'bg-blue-500'}"></div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">${escapeHtml(activity.action)}</p>
                    <p class="text-xs text-gray-600 mt-1">${escapeHtml(activity.user)}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ${activity.time}
                    </p>
                </div>
            </div>
        `).join('');
    }

    function updateAlerts(payments, members) {
        const alerts = [];
        
        const overduePayments = payments.filter(p => p.status === 'Overdue');
        if (overduePayments.length > 0) {
            alerts.push({
                message: `${overduePayments.length} overdue payments require attention`,
                type: "urgent",
                priority: "high"
            });
        }
        
        const pendingPayments = payments.filter(p => p.status === 'Pending');
        if (pendingPayments.length > 0) {
            alerts.push({
                message: `${pendingPayments.length} pending payments waiting for approval`,
                type: "warning",
                priority: "medium"
            });
        }
        
        if (alerts.length === 0) {
            alerts.push({
                message: "All systems operational. No pending issues.",
                type: "success",
                priority: "low"
            });
        }
        
        const container = document.getElementById('alertsList');
        container.innerHTML = alerts.map(alert => `
            <div class="p-4 rounded-lg border-l-4 ${alert.type === 'urgent' ? 'bg-red-50 border-red-500' : alert.type === 'warning' ? 'bg-yellow-50 border-yellow-500' : alert.type === 'success' ? 'bg-green-50 border-green-500' : 'bg-blue-50 border-blue-500'}">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 ${alert.type === 'urgent' ? 'text-red-600' : alert.type === 'warning' ? 'text-yellow-600' : alert.type === 'success' ? 'text-green-600' : 'text-blue-600'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm ${alert.type === 'urgent' ? 'text-red-900' : alert.type === 'warning' ? 'text-yellow-900' : alert.type === 'success' ? 'text-green-900' : 'text-blue-900'}">${escapeHtml(alert.message)}</p>
                        <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full ${alert.priority === 'high' ? 'bg-red-200 text-red-800' : alert.priority === 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-800'}">
                            ${alert.priority === 'high' ? 'Urgent' : alert.priority === 'medium' ? 'Warning' : 'Info'}
                        </span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function formatRelativeTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
        if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    }
    
    function parseRelativeTime(timeString) {
        if (timeString === 'Just now') return 0;
        const mins = timeString.match(/(\d+) minute/);
        if (mins) return parseInt(mins[1]);
        const hours = timeString.match(/(\d+) hour/);
        if (hours) return parseInt(hours[1]) * 60;
        const days = timeString.match(/(\d+) day/);
        if (days) return parseInt(days[1]) * 24 * 60;
        return 999999;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initial load
    loadDashboardData();
    
    // Auto-refresh every 60 seconds
    setInterval(loadDashboardData, 60000);
</script>
@endpush
@endsection