<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Trainer Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl text-gray-900">My Trainer Schedule</h1>
                    <p class="text-gray-600 mt-1">View and manage your training sessions with your personal trainer</p>
                </div>

                                <!-- Stats Cards - Icon and Title top-left, Values bottom-right -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Sessions Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#0070FF] p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Total Sessions</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-2xl text-gray-900 font-bold" id="totalSessions">0</h3>
                        </div>
                    </div>

                    <!-- Upcoming Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#FF9800] p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Upcoming</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-2xl text-gray-900 font-bold" id="upcomingCount">0</h3>
                        </div>
                    </div>

                    <!-- Completed Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#10B981] p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Completed</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-2xl text-gray-900 font-bold" id="completedCount">0</h3>
                        </div>
                    </div>
                </div>
                <!-- Filter Tabs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                    <div class="flex items-center gap-2">
                        <button onclick="filterSessions('All')" id="filterAll" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-[#0070FF] text-white">
                            All Sessions
                        </button>
                        <button onclick="filterSessions('Scheduled')" id="filterUpcoming" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-100">
                            Upcoming (<span id="upcomingFilterCount">0</span>)
                        </button>
                        <button onclick="filterSessions('Completed')" id="filterCompleted" class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-100">
                            Completed (<span id="completedFilterCount">0</span>)
                        </button>
                    </div>
                </div>

                <!-- Sessions List -->
                <div id="sessionsList" class="space-y-4">
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0070FF]"></div>
                        <p class="mt-2 text-gray-600">Loading your sessions...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="bg-[#E6F0FF] p-2 rounded-lg">
                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-gray-900">Reschedule Session</h2>
                        <p class="text-sm text-gray-600" id="modalTrainerName">with Trainer</p>
                    </div>
                </div>
                <button onclick="closeRescheduleModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Current Schedule</p>
                    <p class="text-gray-900 font-medium" id="currentSchedule"></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2 font-medium">New Date</label>
                        <input type="date" id="rescheduleDate" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-2 font-medium">New Time</label>
                        <input type="time" id="rescheduleTime" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-900">
                        <strong>Note:</strong> Rescheduling is subject to trainer availability. You may receive a confirmation notification.
                    </p>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button onclick="closeRescheduleModal()" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="saveReschedule()" class="flex-1 px-4 py-3 bg-[#0070FF] hover:bg-[#005FCC] text-white rounded-lg transition-colors font-medium">
                    Confirm Reschedule
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let sessions = [];
        let currentFilter = "All";
        let selectedSessionId = null;
        let autoRefreshInterval = null;

        // Load sessions from database - Show ALL sessions that are paid
        async function loadSessions() {
            try {
                const response = await fetch('{{ route("member.schedules") }}');
                if (!response.ok) {
                    throw new Error('Failed to load sessions');
                }
                const data = await response.json();
                
                console.log('Sessions loaded from database:', data);
                
                // Show sessions that are PAID and not cancelled
                // Also show sessions that have paymentStatus = "Paid"
                sessions = data.filter(session => {
                    const isPaid = session.paymentStatus === "Paid";
                    const isNotCancelled = session.status !== "Cancelled";
                    return isPaid && isNotCancelled;
                });
                
                console.log('Filtered sessions (Paid only):', sessions);
                
                updateStats();
                renderSessions();
            } catch (error) {
                console.error('Error loading sessions:', error);
                document.getElementById('sessionsList').innerHTML = `
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 text-lg mb-2">Error loading sessions</h3>
                        <p class="text-gray-600">Please refresh the page to try again.</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-[#0070FF] text-white rounded-lg">Refresh Page</button>
                    </div>
                `;
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                weekday: "long",
                month: "long",
                day: "numeric",
                year: "numeric",
            });
        }

        function updateStats() {
            const total = sessions.length;
            const upcoming = sessions.filter(s => s.status === "Scheduled").length;
            const completed = sessions.filter(s => s.status === "Completed").length;
            
            document.getElementById('totalSessions').textContent = total;
            document.getElementById('upcomingCount').textContent = upcoming;
            document.getElementById('completedCount').textContent = completed;
            document.getElementById('upcomingFilterCount').textContent = upcoming;
            document.getElementById('completedFilterCount').textContent = completed;
        }

        function getStatusColor(status) {
            switch(status) {
                case 'Scheduled':
                    return 'bg-blue-100 text-blue-800';
                case 'Completed':
                    return 'bg-green-100 text-green-800';
                case 'Cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        function getStatusText(status) {
            switch(status) {
                case 'Scheduled':
                    return 'Upcoming';
                case 'Completed':
                    return 'Completed';
                case 'Cancelled':
                    return 'Cancelled';
                default:
                    return status;
            }
        }

        function getCardBorder(status) {
            switch(status) {
                case 'Scheduled':
                    return 'border-[#0070FF] bg-gradient-to-r from-[#E6F0FF] to-white';
                case 'Cancelled':
                    return 'border-red-200 bg-red-50';
                default:
                    return 'border-gray-200';
            }
        }

        function renderSessions() {
            const filtered = sessions.filter(session => {
                if (currentFilter === "All") return true;
                if (currentFilter === "Scheduled") return session.status === "Scheduled";
                if (currentFilter === "Completed") return session.status === "Completed";
                return true;
            });

            const container = document.getElementById('sessionsList');
            
            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 text-lg mb-2">No sessions found</h3>
                        <p class="text-gray-600">
                            ${currentFilter === "Scheduled" ? "You don't have any upcoming sessions scheduled." : 
                              currentFilter === "Completed" ? "You haven't completed any sessions yet." : 
                              "You don't have any training sessions. Book a session with a trainer to get started!"}
                        </p>
                        <button onclick="window.location.href='{{ route('available-trainers') }}'" class="mt-4 px-4 py-2 bg-[#0070FF] text-white rounded-lg">
                            Browse Trainers
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(session => `
                <div class="bg-white rounded-xl shadow-sm border transition-all hover:shadow-md ${getCardBorder(session.status)}">
                    <div class="p-6">
                        <div class="flex items-start gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                </svg>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-lg text-gray-900 font-medium">${escapeHtml(session.trainerName)}</h3>
                                        <p class="text-sm text-gray-600 mt-1">${escapeHtml(session.sessionType)}</p>
                                        ${session.paymentStatus === 'Paid' ? '<span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>' : ''}
                                    </div>
                                    <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-medium ${getStatusColor(session.status)}">
                                        ${getStatusText(session.status)}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Date</p>
                                            <p class="text-sm text-gray-900 font-medium">${formatDate(session.sessionDate)}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Time & Duration</p>
                                            <p class="text-sm text-gray-900 font-medium">${escapeHtml(session.sessionTime)} • ${escapeHtml(session.duration)}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Location</p>
                                            <p class="text-sm text-gray-900 font-medium">${escapeHtml(session.location)}</p>
                                        </div>
                                    </div>
                                </div>

                                ${session.status === "Scheduled" ? `
                                    <div class="flex gap-3">
                                        <button onclick="openRescheduleModal(${session.id})" class="flex items-center gap-2 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Reschedule
                                        </button>
                                        <button onclick="cancelSession(${session.id})" class="flex items-center gap-2 px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancel Session
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function filterSessions(filter) {
            currentFilter = filter;
            
            const allBtn = document.getElementById('filterAll');
            const upcomingBtn = document.getElementById('filterUpcoming');
            const completedBtn = document.getElementById('filterCompleted');
            
            allBtn.className = filter === "All" ? "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-[#0070FF] text-white" : "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-100";
            upcomingBtn.className = filter === "Scheduled" ? "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-[#0070FF] text-white" : "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-100";
            completedBtn.className = filter === "Completed" ? "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-[#0070FF] text-white" : "flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-100";
            
            renderSessions();
        }

        function openRescheduleModal(sessionId) {
            const session = sessions.find(s => s.id === sessionId);
            if (session) {
                selectedSessionId = sessionId;
                document.getElementById('modalTrainerName').innerHTML = `with ${escapeHtml(session.trainerName)}`;
                document.getElementById('currentSchedule').innerHTML = `${formatDate(session.sessionDate)} at ${escapeHtml(session.sessionTime)}`;
                document.getElementById('rescheduleDate').value = session.sessionDate;
                document.getElementById('rescheduleTime').value = session.sessionTime;
                document.getElementById('rescheduleModal').classList.remove('hidden');
                document.getElementById('rescheduleModal').classList.add('flex');
            }
        }

        function closeRescheduleModal() {
            document.getElementById('rescheduleModal').classList.add('hidden');
            document.getElementById('rescheduleModal').classList.remove('flex');
            selectedSessionId = null;
        }

        async function saveReschedule() {
            const newDate = document.getElementById('rescheduleDate').value;
            const newTime = document.getElementById('rescheduleTime').value;
            
            if (selectedSessionId && newDate && newTime) {
                try {
                    const response = await fetch(`/member/schedules/${selectedSessionId}/reschedule`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session_date: newDate,
                            session_time: newTime
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        alert('Session rescheduled successfully!');
                        closeRescheduleModal();
                        loadSessions(); // Reload sessions from database
                    } else {
                        alert(data.message || 'Error rescheduling session');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error rescheduling session');
                }
            }
        }

        async function cancelSession(sessionId) {
            if (confirm('Are you sure you want to cancel this session?')) {
                try {
                    const response = await fetch(`/member/schedules/${sessionId}/cancel`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        alert('Session cancelled successfully!');
                        loadSessions(); // Reload sessions from database
                    } else {
                        alert(data.message || 'Error cancelling session');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error cancelling session');
                }
            }
        }

        // Initialize and set up auto-refresh
        document.addEventListener('DOMContentLoaded', function() {
            loadSessions();
            
            // Auto-refresh sessions every 30 seconds to check for new paid sessions
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            autoRefreshInterval = setInterval(() => {
                console.log('Auto-refreshing sessions...');
                loadSessions();
            }, 30000); // Refresh every 30 seconds
        });
    </script>
    @endpush
</x-app-layout>