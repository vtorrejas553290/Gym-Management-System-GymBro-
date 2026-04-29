<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Trainer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="myTrainerApp" class="space-y-6">
                <!-- Loading state -->
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0070FF]"></div>
                    <p class="mt-2 text-gray-600">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        let hiredTrainer = null;
        let sessionDate = "";
        let sessionTime = "";
        let sessionDuration = "1 hour";
        let bookedHours = 1;

        // Load hired trainer from localStorage
        function loadHiredTrainer() {
            try {
                const stored = localStorage.getItem("hiredTrainer");
                console.log('Stored trainer from localStorage:', stored);
                
                if (stored && stored !== "undefined" && stored !== "null") {
                    hiredTrainer = JSON.parse(stored);
                    console.log('Hired trainer parsed:', hiredTrainer);
                    
                    // If trainer has ID, fetch real stats
                    if (hiredTrainer.id) {
                        fetchTrainerStats(hiredTrainer.id);
                    } else {
                        console.log('No trainer ID found, using default stats');
                        renderContent();
                    }
                } else {
                    console.log('No trainer found in localStorage');
                    renderContent();
                }
            } catch (e) {
                console.error("Error loading trainer:", e);
                hiredTrainer = null;
                renderContent();
            }
        }

        // Fetch trainer stats from database
        async function fetchTrainerStats(trainerId) {
            try {
                console.log('Fetching stats for trainer ID:', trainerId);
                const response = await fetch(`/member/trainer-stats/${trainerId}`);
                console.log('Response status:', response.status);
                
                if (response.ok) {
                    const stats = await response.json();
                    console.log('Trainer stats received:', stats);
                    
                    // Update hiredTrainer with real stats
                    hiredTrainer.totalClients = stats.total_clients || 0;
                    hiredTrainer.sessionsCompleted = stats.sessions_completed || 0;
                } else {
                    console.error('Failed to fetch stats, status:', response.status);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    hiredTrainer.totalClients = 0;
                    hiredTrainer.sessionsCompleted = 0;
                }
            } catch (error) {
                console.error('Error fetching trainer stats:', error);
                hiredTrainer.totalClients = 0;
                hiredTrainer.sessionsCompleted = 0;
            }
            renderContent();
        }

        function getCurrentTrainerInfo() {
            return hiredTrainer || defaultTrainerInfo;
        }

        const defaultTrainerInfo = {
            id: null,
            name: "No Trainer Assigned",
            specialty: "N/A",
            specialization: "N/A",
            yearsExperience: 0,
            experience: "0 years",
            email: "N/A",
            phone: "N/A",
            hourlyRate: 0,
            totalClients: 0,
            sessionsCompleted: 0,
            specializations: [],
        };

        function getSafeTrainerInfo() {
            const info = getCurrentTrainerInfo();
            return {
                ...info,
                specializations: info.specializations || [],
                totalClients: info.totalClients || 0,
                sessionsCompleted: info.sessionsCompleted || 0,
            };
        }

        function getTotalPayment() {
            return bookedHours * (getCurrentTrainerInfo().hourlyRate || 0);
        }

        function handleRemoveTrainer() {
            // Close modal first
            closeRemoveModal();
            
            // Remove from localStorage
            localStorage.removeItem("hiredTrainer");
            hiredTrainer = null;
            
            // Show success message
            alert("Trainer removed successfully! You can hire a new trainer from the Personal Trainers page.");
            
            // Re-render the page
            renderContent();
        }

        async function handleConfirmAndPay() {
            if (!sessionDate || !sessionTime) {
                alert("Please select date and time for your session");
                return;
            }

            const currentTrainer = getCurrentTrainerInfo();
            
            if (!currentTrainer.id) {
                alert("Please hire a trainer first");
                return;
            }
            
            // First create the schedule
            const scheduleResponse = await fetch('/member/schedules', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    trainer_id: currentTrainer.id,
                    session_type: currentTrainer.specialty || currentTrainer.specialization || "Personal Training",
                    session_date: sessionDate,
                    session_time: sessionTime,
                    duration: sessionDuration,
                    location: "Main Gym Floor - Zone A",
                    amount: getTotalPayment(),
                    hours: bookedHours
                })
            });
            
            if (scheduleResponse.ok) {
                const scheduleData = await scheduleResponse.json();
                const scheduleId = scheduleData.schedule.id;
                
                // Create session data with schedule_id
                const sessionData = {
                    trainer_id: currentTrainer.id,
                    trainer_name: currentTrainer.name,
                    session_type: currentTrainer.specialty || currentTrainer.specialization || "Personal Training",
                    session_date: sessionDate,
                    session_time: sessionTime,
                    duration: sessionDuration,
                    location: "Main Gym Floor - Zone A",
                    amount: getTotalPayment(),
                    hours: bookedHours,
                    schedule_id: scheduleId
                };

                // Store session data in localStorage
                localStorage.setItem("pendingSession", JSON.stringify(sessionData));
                
                // Redirect to payment page
                window.location.href = "{{ route('payment') }}?action=pay_trainer";
            } else {
                const error = await scheduleResponse.json();
                alert("Error creating session: " + (error.message || "Please try again"));
            }
        }

                function showRemoveModal() {
            const info = getCurrentTrainerInfo();
            if (!info.id) return;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('removeModal');
            if (existingModal) existingModal.remove();
            
            const modalHtml = `
                <div id="removeModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="bg-red-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl text-gray-900 mb-2">Remove Trainer?</h3>
                                <p class="text-sm text-gray-600">
                                    Are you sure you want to remove <strong>${escapeHtml(info.name)}</strong> as your personal trainer?
                                </p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <p class="text-sm text-gray-700 mb-2"><strong>Note:</strong> After removing this trainer:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Your scheduled sessions will remain in your schedule</li>
                                <li>• You can hire a new trainer anytime</li>
                                <li>• All trainer information will be cleared</li>
                            </ul>
                        </div>
                        <div class="flex gap-3">
                            <button id="cancelRemoveBtn" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </button>
                            <button id="confirmRemoveBtn" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Confirm Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Add event listeners
            const confirmBtn = document.getElementById('confirmRemoveBtn');
            const cancelBtn = document.getElementById('cancelRemoveBtn');
            
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleRemoveTrainer();
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeRemoveModal();
                });
            }
        }

        function closeRemoveModal() {
            const modal = document.getElementById('removeModal');
            if (modal) {
                modal.remove();
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderNoTrainer() {
            return `
                <div class="space-y-6" style="min-height: 400px;">
                    <div>
                        <h1 class="text-3xl text-gray-900">My Trainer</h1>
                        <p class="text-gray-600 mt-1">Hire a personal trainer to get started</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl text-gray-900 mb-3">No Trainer Assigned</h2>
                            <p class="text-gray-600 mb-6">
                                You haven't hired a personal trainer yet. Browse our selection of certified trainers and find the perfect match for your fitness goals.
                            </p>
                            <button onclick="window.location.href='{{ route('available-trainers') }}'" class="px-6 py-3 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors font-medium inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Browse Trainers
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderTrainerContent() {
            const info = getCurrentTrainerInfo();
            const safeInfo = getSafeTrainerInfo();

            return `
                <div class="space-y-6">
                    <!-- Header with Remove Button -->
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-3xl text-gray-900">My Trainer</h1>
                            <p class="text-gray-600 mt-1">Your personal fitness coach and training details</p>
                        </div>
                        ${info.id ? `
                        <button onclick="showRemoveModal()" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Remove Trainer
                        </button>
                        ` : ''}
                    </div>

                    <!-- Trainer Profile Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] px-6 py-6 text-white">
                            <div class="flex items-start gap-6 flex-wrap">
                                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h2 class="text-2xl font-semibold">${escapeHtml(info.name)}</h2>
                                        ${info.id ? `<span class="px-3 py-1 bg-green-500/20 text-green-100 border border-green-400/30 rounded-full text-xs font-medium">Active Trainer</span>` : ''}
                                    </div>
                                    <p class="text-white/90 mb-3">${escapeHtml(info.specialty || info.specialization || "Fitness Trainer")}</p>
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <span class="text-white/90">${info.experience || info.yearsExperience + " years"} experience</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Contact Info Column -->
                                <div class="space-y-4">
                                    <h3 class="text-gray-900 font-medium mb-3">Contact Information</h3>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Email</p>
                                            <p class="text-sm text-gray-900">${info.email || "Not provided"}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Phone</p>
                                            <p class="text-sm text-gray-900">${info.phone || "Not provided"}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-[#E6F0FF] rounded-lg">
                                        <div class="bg-[#0070FF] p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Hourly Rate</p>
                                            <p class="text-lg text-gray-900 font-bold">₱${info.hourlyRate}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats and Specializations Column -->
                                <div class="lg:col-span-2 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-600">Total Clients</p>
                                            </div>
                                            <p class="text-2xl text-gray-900 font-bold">${safeInfo.totalClients}</p>
                                            <p class="text-xs text-gray-500 mt-1">Members trained</p>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-600">Sessions Completed</p>
                                            </div>
                                            <p class="text-2xl text-gray-900 font-bold">${safeInfo.sessionsCompleted}</p>
                                            <p class="text-xs text-gray-500 mt-1">Total training sessions</p>
                                        </div>
                                    </div>

                                    <!-- Specializations -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                                <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16M8 4v4m8-4v4M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600">Specializations</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            ${safeInfo.specializations.length > 0 ? safeInfo.specializations.map(spec => `
                                                <span class="px-3 py-1.5 bg-[#E6F0FF] text-[#0070FF] rounded-lg text-sm font-medium">${escapeHtml(spec)}</span>
                                            `).join('') : `
                                                <span class="px-3 py-1.5 bg-[#E6F0FF] text-[#0070FF] rounded-lg text-sm font-medium">${escapeHtml(info.specialty || info.specialization || "Fitness Training")}</span>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary Section - Only show if trainer is hired -->
                    ${info.id && info.hourlyRate > 0 ? `
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] px-6 py-4 text-white">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 backdrop-blur-sm p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Book a Session</h3>
                                    <p class="text-white/90 text-sm">Schedule your training session</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Left Column - Booking Details -->
                                <div class="space-y-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm text-gray-600">Hourly Rate</span>
                                            <span class="text-2xl text-gray-900 font-bold">₱${info.hourlyRate}</span>
                                        </div>
                                        <div class="h-px bg-gray-200 my-3"></div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Per Hour</span>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-900">60 minutes</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                        <label class="text-sm text-gray-700 mb-2 block font-medium flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Session Date
                                        </label>
                                        <input type="date" id="sessionDateInput" min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-gray-900">
                                    </div>

                                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                        <label class="text-sm text-gray-700 mb-2 block font-medium flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Session Time
                                        </label>
                                        <input type="time" id="sessionTimeInput" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-gray-900">
                                    </div>

                                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                        <label class="text-sm text-gray-700 mb-2 block font-medium flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Duration
                                        </label>
                                        <select id="durationSelect" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-gray-900 cursor-pointer">
                                            <option value="1 hour">1 hour</option>
                                            <option value="1.5 hours">1.5 hours</option>
                                            <option value="2 hours">2 hours</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right Column - Payment Summary -->
                                <div class="space-y-4">
                                    <div class="p-6 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-xl text-white">
                                        <h4 class="text-sm text-white/80 mb-4">Payment Summary</h4>
                                        <div class="space-y-3 mb-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/90">Hourly Rate</span>
                                                <span class="text-white font-medium">₱${info.hourlyRate}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-white/90">Number of Hours</span>
                                                <span class="text-white font-medium">× <span id="bookedHoursDisplay">1</span></span>
                                            </div>
                                            <div class="h-px bg-white/20 my-2"></div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-lg font-semibold">Total Amount</span>
                                                <span class="text-2xl font-bold" id="totalAmountDisplay">₱${info.hourlyRate}</span>
                                            </div>
                                        </div>
                                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 flex items-start gap-2">
                                            <svg class="w-5 h-5 text-green-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-white/90">You will be redirected to payment page to complete your booking</p>
                                        </div>
                                    </div>

                                    <button id="confirmPayBtn" class="w-full px-6 py-3 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-all font-semibold flex items-center justify-center gap-2 shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Proceed to Payment
                                    </button>
                                    <p id="validationMsg" class="text-xs text-center text-red-500 hidden">Please select date and time to continue</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#E6F0FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-gray-900 font-medium">View Full Schedule</h3>
                                    <p class="text-sm text-gray-600">See all your upcoming sessions</p>
                                </div>
                                <button onclick="window.location.href='{{ route('my-schedule') }}'" class="px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors text-sm font-medium">
                                    Go
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="bg-[#0070FF] p-2 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-900 font-medium mb-2">Booking Process</h3>
                                <p class="text-sm text-gray-600 mb-1">1. Select your preferred date and time</p>
                                <p class="text-sm text-gray-600 mb-1">2. Click "Proceed to Payment"</p>
                                <p class="text-sm text-gray-600 mb-1">3. Complete payment to confirm your session</p>
                                <p class="text-sm text-gray-600">4. View your confirmed session in "My Schedule"</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        function attachEventListeners() {
            const sessionDateInput = document.getElementById('sessionDateInput');
            const sessionTimeInput = document.getElementById('sessionTimeInput');
            const durationSelect = document.getElementById('durationSelect');
            const confirmPayBtn = document.getElementById('confirmPayBtn');
            const totalAmountDisplay = document.getElementById('totalAmountDisplay');
            const bookedHoursDisplay = document.getElementById('bookedHoursDisplay');
            const validationMsg = document.getElementById('validationMsg');
            const info = getCurrentTrainerInfo();
            
            function validateForm() {
                const confirmBtn = document.getElementById('confirmPayBtn');
                const msg = document.getElementById('validationMsg');
                if (sessionDate && sessionTime) {
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    if (msg) msg.classList.add('hidden');
                } else {
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                    if (msg) msg.classList.remove('hidden');
                }
            }
            
            if (sessionDateInput) {
                sessionDateInput.addEventListener('change', function(e) {
                    sessionDate = e.target.value;
                    validateForm();
                });
            }
            
            if (sessionTimeInput) {
                sessionTimeInput.addEventListener('change', function(e) {
                    sessionTime = e.target.value;
                    validateForm();
                });
            }
            
            if (durationSelect) {
                durationSelect.addEventListener('change', function(e) {
                    sessionDuration = e.target.value;
                    const hours = parseFloat(e.target.value);
                    bookedHours = hours;
                    if (bookedHoursDisplay) bookedHoursDisplay.textContent = hours;
                    const total = hours * info.hourlyRate;
                    if (totalAmountDisplay) totalAmountDisplay.textContent = `₱${total.toLocaleString()}`;
                });
            }
            
            if (confirmPayBtn && info.id) {
                // Remove existing listeners to avoid duplicates
                const newConfirmPayBtn = confirmPayBtn.cloneNode(true);
                confirmPayBtn.parentNode.replaceChild(newConfirmPayBtn, confirmPayBtn);
                newConfirmPayBtn.addEventListener('click', handleConfirmAndPay);
            }
            
            // Initial validation
            validateForm();
        }

        function renderContent() {
            const container = document.getElementById('myTrainerApp');
            if (!container) return;
            
            if (!hiredTrainer || !hiredTrainer.id) {
                container.innerHTML = renderNoTrainer();
            } else {
                container.innerHTML = renderTrainerContent();
                attachEventListeners();
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing my-trainer page...');
            loadHiredTrainer();
        });
    </script>
    @endpush
</x-app-layout>