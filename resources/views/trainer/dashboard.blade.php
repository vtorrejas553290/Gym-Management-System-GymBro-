@extends('layouts.trainer-app')

@section('content')
<div class="w-full space-y-4 md:space-y-6">
    <!-- Header -->
    <div class="w-full">
        <h1 class="text-xl md:text-3xl text-gray-900">Trainer Dashboard</h1>
        <p class="text-gray-600 mt-1 text-sm md:text-base">Welcome back, {{ Auth::user()->first_name }}! Here's your overview for today.</p>
    </div>

    <!-- Summary Cards - Icon and Title top-left, Values bottom-right -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 w-full">
        <!-- Total Assigned Clients Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 flex flex-col hover:shadow-md transition-shadow w-full">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-[#0070FF] p-2 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-gray-600 font-medium">Total Assigned Clients</p>
                </div>
            </div>
            <div class="mt-auto text-right">
                <h3 class="text-xl md:text-3xl text-gray-900 font-bold" id="totalClients">0</h3>
                <p class="text-xs md:text-sm text-[#0070FF] mt-1" id="clientChange">Loading...</p>
            </div>
        </div>

        <!-- Today's Sessions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 flex flex-col hover:shadow-md transition-shadow w-full">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-[#00BFA5] p-2 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-gray-600 font-medium">Today's Sessions</p>
                </div>
            </div>
            <div class="mt-auto text-right">
                <h3 class="text-xl md:text-3xl text-gray-900 font-bold" id="todaySessions">0</h3>
                <p class="text-xs md:text-sm text-[#00BFA5] mt-1" id="todayRemaining">Loading...</p>
            </div>
        </div>

        <!-- Completed Sessions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 flex flex-col hover:shadow-md transition-shadow w-full">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-[#9C27B0] p-2 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-gray-600 font-medium">Completed Sessions</p>
                </div>
            </div>
            <div class="mt-auto text-right">
                <h3 class="text-xl md:text-3xl text-gray-900 font-bold" id="completedSessions">0</h3>
                <p class="text-xs md:text-sm text-[#9C27B0] mt-1" id="completedChange">Loading...</p>
            </div>
        </div>

        <!-- Pending Sessions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 flex flex-col hover:shadow-md transition-shadow w-full">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-[#FF9800] p-2 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-gray-600 font-medium">Pending Approval</p>
                </div>
            </div>
            <div class="mt-auto text-right">
                <h3 class="text-xl md:text-3xl text-gray-900 font-bold" id="pendingSessions">0</h3>
                <p class="text-xs md:text-sm text-[#FF9800] mt-1">Awaiting confirmation</p>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <h3 class="text-base md:text-lg text-gray-900 font-semibold">Today's Schedule</h3>
            <p class="text-sm text-gray-600 mt-1" id="todayDate"></p>
        </div>
        <div class="p-4 md:p-6">
            <div id="todaySessionsList" class="space-y-3 md:space-y-4 w-full">
                <div class="text-center py-8 w-full">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#0070FF]"></div>
                    <p class="mt-2 text-sm text-gray-600">Loading sessions...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Clients -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <h3 class="text-base md:text-lg text-gray-900 font-semibold">Active Clients</h3>
            <p class="text-sm text-gray-600 mt-1">Quick overview of your assigned clients</p>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[500px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Client Name</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Plan</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Next Session</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs text-gray-600 uppercase跟踪-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="activeClientsList" class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Loading clients...<\/td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 w-full">
        <div onclick="window.location.href='{{ route('trainer.my-schedule') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 hover:shadow-md transition-shadow cursor-pointer w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-[#E6F0FF] p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0070FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-gray-900 font-medium text-sm md:text-base">View Schedule</h4>
                    <p class="text-xs md:text-sm text-gray-600 mt-0.5">Check your sessions</p>
                </div>
            </div>
        </div>

        <div onclick="window.location.href='{{ route('trainer.my-clients') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 hover:shadow-md transition-shadow cursor-pointer w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-[#E6F0FF] p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0070FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-gray-900 font-medium text-sm md:text-base">Manage Clients</h4>
                    <p class="text-xs md:text-sm text-gray-600 mt-0.5">View client progress</p>
                </div>
            </div>
        </div>

        <div onclick="window.location.href='{{ route('trainer.profile') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 hover:shadow-md transition-shadow cursor-pointer w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-[#E6F0FF] p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0070FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-gray-900 font-medium text-sm md:text-base">My Profile</h4>
                    <p class="text-xs md:text-sm text-gray-600 mt-0.5">Update your details</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load dashboard data
    async function loadDashboardData() {
        try {
            // Load stats
            const statsResponse = await fetch('/trainer/stats');
            if (statsResponse.ok) {
                const stats = await statsResponse.json();
                
                document.getElementById('totalClients').innerText = stats.total_clients || 0;
                document.getElementById('todaySessions').innerText = stats.today_sessions || 0;
                document.getElementById('completedSessions').innerText = stats.completed_sessions || 0;
                document.getElementById('pendingSessions').innerText = stats.pending_sessions || 0;
                
                document.getElementById('clientChange').innerText = `+${stats.total_clients_growth || 0} this month`;
                document.getElementById('todayRemaining').innerText = `${stats.remaining_today || 0} remaining`;
                document.getElementById('completedChange').innerText = `+${stats.completed_this_week || 0} this week`;
            } else {
                console.error('Failed to load stats');
                // Set default values if API fails
                document.getElementById('totalClients').innerText = '0';
                document.getElementById('todaySessions').innerText = '0';
                document.getElementById('completedSessions').innerText = '0';
                document.getElementById('pendingSessions').innerText = '0';
                document.getElementById('clientChange').innerText = 'No data available';
                document.getElementById('todayRemaining').innerText = 'No data available';
                document.getElementById('completedChange').innerText = 'No data available';
            }
            
            // Set today's date
            const today = new Date();
            document.getElementById('todayDate').innerText = today.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            // Load today's sessions
            await loadTodaySessions();
            
            // Load active clients
            await loadActiveClients();
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            showErrorMessage();
        }
    }
    
    function showErrorMessage() {
        const container = document.getElementById('todaySessionsList');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-8 w-full">
                    <p class="text-red-500 text-sm">Error loading dashboard data. Please refresh the page.</p>
                </div>
            `;
        }
    }
    
    async function loadTodaySessions() {
        try {
            const response = await fetch('/trainer/today-sessions');
            if (response.ok) {
                const sessions = await response.json();
                const container = document.getElementById('todaySessionsList');
                
                if (!sessions || sessions.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 w-full">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No sessions scheduled for today</p>
                            <p class="text-xs text-gray-400 mt-1">Enjoy your free time! 🎉</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                for (let session of sessions) {
                    const statusClass = session.status === 'Completed' ? 'bg-gray-50 border-gray-200' : 'bg-[#E6F0FF] border-[#0070FF]';
                    const statusBadge = session.status === 'Completed' 
                        ? '<span class="inline-flex px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">Completed</span>'
                        : '<span class="inline-flex px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Upcoming</span>';
                    
                    html += `
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-lg border transition-all ${statusClass} gap-3 w-full">
                            <div class="flex items-center gap-3 md:gap-4 flex-1">
                                <div class="bg-[#0070FF] p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium text-sm md:text-base">${escapeHtml(session.time)}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">${escapeHtml(session.member_name)}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4 flex-wrap">
                                <div class="text-right">
                                    <p class="text-sm text-gray-900 font-medium">${escapeHtml(session.session_type)}</p>
                                    <p class="text-xs text-gray-500">${escapeHtml(session.duration)}</p>
                                </div>
                                ${statusBadge}
                            </div>
                        </div>
                    `;
                }
                
                container.innerHTML = html;
            } else {
                throw new Error('Failed to load sessions');
            }
        } catch (error) {
            console.error('Error loading today\'s sessions:', error);
            const container = document.getElementById('todaySessionsList');
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-8 w-full">
                        <p class="text-red-500 text-sm">Error loading sessions. Please try refreshing the page.</p>
                    </div>
                `;
            }
        }
    }
    
    async function loadActiveClients() {
        try {
            const response = await fetch('/trainer/active-clients');
            if (response.ok) {
                const clients = await response.json();
                const tbody = document.getElementById('activeClientsList');
                
                if (!clients || clients.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p>No active clients assigned</p>
                                <p class="text-xs mt-1">Clients will appear here once assigned</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                let html = '';
                for (let client of clients) {
                    html += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-white text-xs font-bold">${escapeHtml(client.initials)}</span>
                                    </div>
                                    <span class="text-sm text-gray-900 font-medium">${escapeHtml(client.name)}</span>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">${escapeHtml(client.plan)}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">${escapeHtml(client.next_session)}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-medium">
                                    Active
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <button onclick="viewClient(${client.id})" class="text-[#0070FF] hover:text-[#005FCC] font-medium text-sm transition-colors">
                                    View Details →
                                </button>
                            </td>
                        </tr>
                    `;
                }
                
                tbody.innerHTML = html;
            } else {
                throw new Error('Failed to load clients');
            }
        } catch (error) {
            console.error('Error loading active clients:', error);
            const tbody = document.getElementById('activeClientsList');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-red-500">
                            Error loading clients. Please refresh the page.
                        </td>
                    </tr>
                `;
            }
        }
    }
    
    function viewClient(clientId) {
        window.location.href = `/trainer/clients/${clientId}`;
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Auto-refresh data every 60 seconds
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            loadDashboardData();
        }, 60000);
    }
    
    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
    
    // Load data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardData();
        startAutoRefresh();
    });
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
</script>
@endpush
@endsection