<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="space-y-4 md:space-y-6">
                <!-- Header -->
                <div>
                    <h1 class="text-xl md:text-3xl text-gray-900">Welcome back, {{ Auth::user()->first_name }}!</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Here's your fitness overview</p>
                </div>

                                                <!-- Summary Cards - Icon and Title top-left, Values bottom-right -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    <!-- Current Plan -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#0070FF] p-2 md:p-3 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 font-medium">Current Plan</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-base md:text-2xl text-gray-900 font-bold" id="currentPlanName">Basic</h3>
                            <p class="text-xs md:text-sm text-[#0070FF] mt-1" id="currentPlanDuration">1 Month</p>
                        </div>
                    </div>

                    <!-- Remaining Days -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#FF9800] p-2 md:p-3 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 font-medium">Remaining Days</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-base md:text-2xl text-gray-900 font-bold" id="remainingDays">0</h3>
                            <p class="text-xs md:text-sm text-[#FF9800] mt-1">Until renewal</p>
                        </div>
                    </div>

                    <!-- Sessions This Week -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#9C27B0] p-2 md:p-3 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 font-medium">Sessions This Week</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-base md:text-2xl text-gray-900 font-bold" id="sessionsThisWeek">0</h3>
                            <p class="text-xs md:text-sm text-[#9C27B0] mt-1" id="sessionsRemaining">0 remaining</p>
                        </div>
                    </div>

                    <!-- Assigned Trainer -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-[#2196F3] p-2 md:p-3 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 font-medium">Assigned Trainer</p>
                        </div>
                        <div class="mt-auto text-right">
                            <h3 class="text-sm md:text-2xl text-gray-900 font-bold" id="trainerName">Not Assigned</h3>
                            <p class="text-xs md:text-sm text-[#2196F3] mt-1" id="trainerSpecialty">-</p>
                        </div>
                    </div>
                </div>

                <!-- Current Membership Banner -->
                <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] rounded-xl shadow-lg p-5 md:p-8 text-white">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="w-full">
                            <div class="flex items-center gap-2 md:gap-3 mb-2">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <h2 class="text-lg md:text-2xl" id="bannerPlanName">Basic Membership</h2>
                            </div>
                            <p class="text-white/90 text-sm md:text-base mb-4" id="bannerPlanDetails">1 Month Plan - Active</p>
                            <div class="flex flex-wrap items-center gap-4 md:gap-6">
                                <div>
                                    <p class="text-xs md:text-sm text-white/80">Start Date</p>
                                    <p class="text-sm md:text-lg font-medium" id="startDate">-</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-white/80">Expires</p>
                                    <p class="text-sm md:text-lg font-medium" id="expiryDate">-</p>
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm text-white/80">Remaining</p>
                                    <p class="text-sm md:text-lg font-medium" id="bannerRemainingDays">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-auto">
                            <button onclick="window.location.href='{{ route('membership') }}'" class="w-full md:w-auto px-5 py-2.5 bg-white text-[#0070FF] rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Sessions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <h3 class="text-base md:text-xl text-gray-900">Upcoming Sessions</h3>
                        <p class="text-sm text-gray-600 mt-1">Your scheduled training sessions</p>
                    </div>
                    <div class="p-4 md:p-6">
                        <div id="sessionsList" class="space-y-3">
                            <div class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 md:h-8 md:w-8 border-b-2 border-[#0070FF]"></div>
                                <p class="mt-2 text-sm text-gray-600">Loading sessions...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions - Only Book a Session and Make Payment -->
                <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-2 md:gap-6">
                    <div onclick="window.location.href='{{ route('available-trainers') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0070FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16M8 4v4m8-4v4M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-medium text-sm md:text-base">Book a Session</h4>
                                <p class="text-xs md:text-sm text-gray-600 mt-0.5">Schedule new training</p>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='{{ route('payment') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0070FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-medium text-sm md:text-base">Make Payment</h4>
                                <p class="text-xs md:text-sm text-gray-600 mt-0.5">Renew membership</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Chart - Removed completely -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Load dashboard data
        function loadDashboardData() {
            // Fetch membership data
            fetch('/member/membership/current')
                .then(response => response.json())
                .then(data => {
                    console.log('Membership data:', data);
                    document.getElementById('currentPlanName').innerHTML = data.plan_name;
                    document.getElementById('currentPlanDuration').innerHTML = data.duration;
                    document.getElementById('remainingDays').innerHTML = data.remaining_days;
                    document.getElementById('bannerPlanName').innerHTML = data.plan_name + ' Membership';
                    document.getElementById('bannerPlanDetails').innerHTML = data.duration + ' Plan - ' + data.status;
                    document.getElementById('startDate').innerHTML = data.start_date;
                    document.getElementById('expiryDate').innerHTML = data.expiry_date;
                    document.getElementById('bannerRemainingDays').innerHTML = data.remaining_days + ' days';
                })
                .catch(error => {
                    console.error('Error loading membership:', error);
                });
            
            // Load trainer from localStorage
            try {
                const hiredTrainer = localStorage.getItem('hiredTrainer');
                if (hiredTrainer && hiredTrainer !== 'undefined' && hiredTrainer !== 'null') {
                    const trainer = JSON.parse(hiredTrainer);
                    document.getElementById('trainerName').innerHTML = trainer.name;
                    document.getElementById('trainerSpecialty').innerHTML = trainer.specialty || trainer.specialization || 'Fitness Trainer';
                }
            } catch(e) {
                console.error('Error loading trainer:', e);
            }
            
            // Load sessions
            fetch('/member/schedules')
                .then(response => response.json())
                .then(sessions => {
                    console.log('Sessions data:', sessions);
                    const upcomingSessions = sessions.filter(s => s.status === 'Scheduled' && s.paymentStatus === 'Paid');
                    
                    document.getElementById('sessionsThisWeek').innerHTML = upcomingSessions.length;
                    document.getElementById('sessionsRemaining').innerHTML = upcomingSessions.length + ' remaining';
                    
                    const container = document.getElementById('sessionsList');
                    
                    if (upcomingSessions.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-8">
                                <div class="bg-gray-100 w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">No upcoming sessions</p>
                                <p class="text-xs text-gray-400 mt-1">Book a session with a trainer to get started</p>
                            </div>
                        `;
                        return;
                    }
                    
                    let html = '';
                    for(let i = 0; i < Math.min(upcomingSessions.length, 3); i++) {
                        const session = upcomingSessions[i];
                        const date = new Date(session.sessionDate);
                        const formattedDate = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                        
                        html += `
                            <div class="bg-[#E6F0FF] border border-[#0070FF] rounded-lg p-4 hover:shadow-sm transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#0070FF] p-2 rounded-lg">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-medium text-sm md:text-base">${escapeHtml(session.sessionType)}</p>
                                            <p class="text-xs text-gray-600 mt-0.5">with ${escapeHtml(session.trainerName)}</p>
                                        </div>
                                    </div>
                                    <div class="sm:text-right">
                                        <p class="text-xs text-gray-900">${formattedDate}</p>
                                        <p class="text-xs text-gray-600 mt-0.5">
                                            ${escapeHtml(session.sessionTime)} • ${escapeHtml(session.duration)}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    if (upcomingSessions.length > 3) {
                        html += `
                            <div class="text-center pt-2">
                                <button onclick="window.location.href='{{ route('my-schedule') }}'" class="text-[#0070FF] text-sm hover:underline">
                                    View all ${upcomingSessions.length} sessions →
                                </button>
                            </div>
                        `;
                    }
                    
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading sessions:', error);
                    document.getElementById('sessionsList').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-500 text-sm">Error loading sessions</p>
                        </div>
                    `;
                });
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });
    </script>
    @endpush
</x-app-layout>