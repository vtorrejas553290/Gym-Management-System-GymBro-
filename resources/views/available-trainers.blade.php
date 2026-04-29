<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personal Trainers') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12 w-full">
        <div class="w-full px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="space-y-4 md:space-y-6">
                <!-- Header -->
                <div>
                    <h1 class="text-xl md:text-3xl text-gray-900">Personal Trainers</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Find and hire the perfect trainer for your fitness goals</p>
                </div>

                <!-- Search and Filter Bar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1 relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search by name or specialization..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-sm md:text-base">
                        </div>

                        <!-- Filter Button (Mobile) -->
                        <button onclick="toggleFilters()" class="lg:hidden px-4 py-3 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors flex items-center justify-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filters
                        </button>

                        <!-- Desktop Filters -->
                        <div class="hidden lg:flex lg:items-center gap-4">
                            <select id="specializationFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent bg-white min-w-[200px] text-sm">
                                <option value="All">All Specializations</option>
                                <option value="Strength Training">Strength Training</option>
                                <option value="Cardio & Weight Loss">Cardio & Weight Loss</option>
                                <option value="Yoga & Flexibility">Yoga & Flexibility</option>
                                <option value="HIIT & Functional Training">HIIT & Functional Training</option>
                                <option value="Bodybuilding & Nutrition">Bodybuilding & Nutrition</option>
                                <option value="Rehabilitation & Recovery">Rehabilitation & Recovery</option>
                            </select>

                            <select id="priceFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent bg-white min-w-[160px] text-sm">
                                <option value="All">All Price Ranges</option>
                                <option value="500-700">₱500 - ₱700</option>
                                <option value="700-900">₱700 - ₱900</option>
                                <option value="900+">₱900+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mobile Filters -->
                    <div id="mobileFilters" class="lg:hidden mt-4 pt-4 border-t border-gray-200 space-y-4" style="display: none;">
                        <div>
                            <label class="block text-sm text-gray-700 mb-2 font-medium">Specialization</label>
                            <select id="mobileSpecializationFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent bg-white text-sm">
                                <option value="All">All Specializations</option>
                                <option value="Strength Training">Strength Training</option>
                                <option value="Cardio & Weight Loss">Cardio & Weight Loss</option>
                                <option value="Yoga & Flexibility">Yoga & Flexibility</option>
                                <option value="HIIT & Functional Training">HIIT & Functional Training</option>
                                <option value="Bodybuilding & Nutrition">Bodybuilding & Nutrition</option>
                                <option value="Rehabilitation & Recovery">Rehabilitation & Recovery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-2 font-medium">Price Range</label>
                            <select id="mobilePriceFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent bg-white text-sm">
                                <option value="All">All Price Ranges</option>
                                <option value="500-700">₱500 - ₱700</option>
                                <option value="700-900">₱700 - ₱900</option>
                                <option value="900+">₱900+</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="flex items-center justify-between">
                    <p class="text-sm md:text-base text-gray-600">
                        Showing <span id="resultsCount" class="text-gray-900 font-medium">0</span> trainers
                    </p>
                </div>

                <!-- Trainers Grid - Responsive -->
                <div id="trainersGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <!-- Trainers will be loaded here -->
                </div>

                <!-- No Results -->
                <div id="noResults" class="text-center py-12 hidden">
                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-gray-900 text-lg mb-2">No trainers found</h3>
                    <p class="text-gray-600 text-sm">Try adjusting your search or filters</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hire Trainer Modal -->
    <div id="hireModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="bg-[#E6F0FF] p-2 rounded-lg">
                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg text-gray-900 font-semibold">Hire Trainer</h2>
                        <p class="text-xs text-gray-600">Confirm your selection</p>
                    </div>
                </div>
                <button onclick="closeHireModal()" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <!-- Trainer Info -->
                <div id="modalTrainerInfo" class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] rounded-lg p-4 text-white">
                    <!-- Dynamic content -->
                </div>

                <!-- Optional Session Scheduling -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h4 class="text-sm text-gray-900 font-medium">Schedule First Session (Optional)</h4>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-700 mb-1 font-medium">Date</label>
                            <input type="date" id="hireDate" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1 font-medium">Time</label>
                            <input type="time" id="hireTime" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">You can schedule more sessions later</p>
                </div>

                <!-- Confirmation Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-xs text-blue-900">
                            <p class="font-medium mb-1">What happens next?</p>
                            <ul class="space-y-0.5">
                                <li>• Trainer will be assigned to you</li>
                                <li>• Book sessions from "My Trainer" page</li>
                                <li>• Change trainers anytime</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 flex gap-3">
                <button onclick="closeHireModal()" class="flex-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                    Cancel
                </button>
                <button onclick="confirmHire()" class="flex-1 px-3 py-2 bg-[#0070FF] hover:bg-[#005FCC] text-white rounded-lg transition-colors text-sm font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Confirm Hire
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        let trainers = [];
        let currentSearch = "";
        let currentSpecialization = "All";
        let currentPriceRange = "All";
        let selectedTrainer = null;

        // Load trainers from database
        async function loadTrainers() {
            try {
                const response = await fetch('/available-trainers/data');
                const data = await response.json();
                trainers = data;
                filterTrainers();
            } catch (error) {
                console.error('Error loading trainers:', error);
                document.getElementById('trainersGrid').innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <p class="text-red-500">Error loading trainers. Please refresh the page.</p>
                    </div>
                `;
            }
        }

        function filterTrainers() {
            const filtered = trainers.filter(trainer => {
                const fullName = `${trainer.first_name} ${trainer.middle_name ? trainer.middle_name + ' ' : ''}${trainer.last_name}`;
                const matchesSearch = currentSearch === "" || 
                    fullName.toLowerCase().includes(currentSearch.toLowerCase()) ||
                    trainer.specialization.toLowerCase().includes(currentSearch.toLowerCase());

                const matchesSpecialization = currentSpecialization === "All" || trainer.specialization === currentSpecialization;

                let matchesPrice = true;
                if (currentPriceRange === "500-700") {
                    matchesPrice = trainer.hourly_rate >= 500 && trainer.hourly_rate <= 700;
                } else if (currentPriceRange === "700-900") {
                    matchesPrice = trainer.hourly_rate > 700 && trainer.hourly_rate <= 900;
                } else if (currentPriceRange === "900+") {
                    matchesPrice = trainer.hourly_rate > 900;
                }

                return matchesSearch && matchesSpecialization && matchesPrice && trainer.status === 'Active';
            });

            renderTrainers(filtered);
            document.getElementById('resultsCount').textContent = filtered.length;
            
            if (filtered.length === 0) {
                document.getElementById('noResults').classList.remove('hidden');
            } else {
                document.getElementById('noResults').classList.add('hidden');
            }
        }

        function getFullName(trainer) {
            let name = trainer.first_name;
            if (trainer.middle_name) {
                name += ' ' + trainer.middle_name;
            }
            name += ' ' + trainer.last_name;
            return name;
        }

        function renderTrainers(trainersList) {
            const container = document.getElementById('trainersGrid');
            
            if (!trainersList || trainersList.length === 0) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = trainersList.map(trainer => {
                const fullName = getFullName(trainer);
                const availability = trainer.status === 'Active' ? 'Available' : 'Booked';
                return `
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200 flex flex-col h-full">
                    <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] p-4 md:p-6 text-white">
                        <div class="flex items-center gap-3 md:gap-4 mb-3 md:mb-4">
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base md:text-xl font-semibold mb-1">${escapeHtml(fullName)}</h3>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 md:px-3 py-1 rounded-full text-xs font-medium ${availability === 'Available' ? 'bg-green-500/20 text-green-100 border border-green-400/30' : 'bg-red-500/20 text-red-100 border border-red-400/30'}">
                                ${availability}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 md:p-6 flex-1 flex flex-col">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 md:mb-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs md:text-sm text-gray-900 font-medium">${escapeHtml(trainer.specialization)}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-2 md:mb-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs md:text-sm text-gray-600">${trainer.experience} years experience</span>
                            </div>
                            <div class="flex items-center gap-2 mb-3 md:mb-4 p-2 md:p-3 bg-[#E6F0FF] rounded-lg">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-600">Hourly Rate</p>
                                    <p class="text-base md:text-xl text-gray-900 font-bold">₱${trainer.hourly_rate.toLocaleString()}</p>
                                </div>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed mb-3 md:mb-4">Professional trainer specializing in ${escapeHtml(trainer.specialization)} with ${trainer.experience} years of experience.</p>
                            <div class="mb-3 md:mb-4">
                                <p class="text-xs text-gray-600 mb-2">Contact</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">${escapeHtml(trainer.email)}</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">${escapeHtml(trainer.phone)}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="openHireModal(${trainer.id})" ${availability !== 'Available' ? 'disabled' : ''} class="w-full py-2 md:py-3 rounded-lg font-medium transition-colors mt-3 md:mt-4 text-sm ${availability === 'Available' ? 'bg-[#0070FF] text-white hover:bg-[#005FCC]' : 'bg-gray-200 text-gray-500 cursor-not-allowed'}">
                            ${availability === "Available" ? "Hire Trainer" : "Currently Unavailable"}
                        </button>
                    </div>
                </div>
            `}).join('');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function openHireModal(trainerId) {
            selectedTrainer = trainers.find(t => t.id === trainerId);
            if (selectedTrainer) {
                const fullName = getFullName(selectedTrainer);
                const modalInfo = document.getElementById('modalTrainerInfo');
                modalInfo.innerHTML = `
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base md:text-lg font-medium">${escapeHtml(fullName)}</h3>
                            <p class="text-white/90 text-xs">${escapeHtml(selectedTrainer.specialization)}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-white/20">
                        <div>
                            <p class="text-white/80 text-xs">Hourly Rate</p>
                            <p class="text-lg md:text-xl font-bold">₱${selectedTrainer.hourly_rate.toLocaleString()}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-xs">Experience</p>
                            <p class="text-sm md:text-base font-medium">${selectedTrainer.experience} years</p>
                        </div>
                    </div>
                `;
                document.getElementById('hireDate').value = '';
                document.getElementById('hireTime').value = '';
                document.getElementById('hireModal').classList.remove('hidden');
                document.getElementById('hireModal').classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeHireModal() {
            document.getElementById('hireModal').classList.add('hidden');
            document.getElementById('hireModal').classList.remove('flex');
            document.body.style.overflow = '';
            selectedTrainer = null;
        }

        async function confirmHire() {
            if (selectedTrainer) {
                const hiredTrainerData = {
                    id: selectedTrainer.id,  // CRITICAL - this must be included
                    name: getFullName(selectedTrainer),
                    specialty: selectedTrainer.specialization,
                    specialization: selectedTrainer.specialization,
                    yearsExperience: selectedTrainer.experience,
                    experience: selectedTrainer.experience,
                    hourlyRate: selectedTrainer.hourly_rate,
                    email: selectedTrainer.email,
                    phone: selectedTrainer.phone,
                    status: "Active",
                    hiredDate: new Date().toISOString(),
                    specializations: [selectedTrainer.specialization],
                };
                        console.log('Saving trainer data with ID:', hiredTrainerData);
                localStorage.setItem("hiredTrainer", JSON.stringify(hiredTrainerData));

                const hireDate = document.getElementById('hireDate').value;
                const hireTime = document.getElementById('hireTime').value;

                if (hireDate && hireTime) {
                    const newSession = {
                        id: Date.now(),
                        date: hireDate,
                        time: hireTime,
                        trainerName: getFullName(selectedTrainer),
                        sessionType: selectedTrainer.specialization,
                        status: "Scheduled",
                        duration: "1 hour",
                        location: "Main Gym Floor - Zone A",
                        paymentStatus: "Pending",
                    };

                    const existingSessions = JSON.parse(localStorage.getItem("trainerSessions") || "[]");
                    localStorage.setItem("trainerSessions", JSON.stringify([newSession, ...existingSessions]));
                }

                alert(`${getFullName(selectedTrainer)} hired successfully! You can now view your trainer in the 'My Trainer' section.`);
                closeHireModal();
                
                setTimeout(() => {
                    window.location.href = "{{ route('my-trainer') }}";
                }, 1000);
            }
        }

        function toggleFilters() {
            const filters = document.getElementById('mobileFilters');
            if (filters.style.display === 'none' || filters.style.display === '') {
                filters.style.display = 'block';
            } else {
                filters.style.display = 'none';
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    currentSearch = e.target.value;
                    filterTrainers();
                });
            }

            // Desktop filters
            const specializationFilter = document.getElementById('specializationFilter');
            if (specializationFilter) {
                specializationFilter.addEventListener('change', function(e) {
                    currentSpecialization = e.target.value;
                    const mobileFilter = document.getElementById('mobileSpecializationFilter');
                    if (mobileFilter) mobileFilter.value = currentSpecialization;
                    filterTrainers();
                });
            }

            const priceFilter = document.getElementById('priceFilter');
            if (priceFilter) {
                priceFilter.addEventListener('change', function(e) {
                    currentPriceRange = e.target.value;
                    const mobileFilter = document.getElementById('mobilePriceFilter');
                    if (mobileFilter) mobileFilter.value = currentPriceRange;
                    filterTrainers();
                });
            }

            // Mobile filters
            const mobileSpecializationFilter = document.getElementById('mobileSpecializationFilter');
            if (mobileSpecializationFilter) {
                mobileSpecializationFilter.addEventListener('change', function(e) {
                    currentSpecialization = e.target.value;
                    const desktopFilter = document.getElementById('specializationFilter');
                    if (desktopFilter) desktopFilter.value = currentSpecialization;
                    filterTrainers();
                });
            }

            const mobilePriceFilter = document.getElementById('mobilePriceFilter');
            if (mobilePriceFilter) {
                mobilePriceFilter.addEventListener('change', function(e) {
                    currentPriceRange = e.target.value;
                    const desktopFilter = document.getElementById('priceFilter');
                    if (desktopFilter) desktopFilter.value = currentPriceRange;
                    filterTrainers();
                });
            }

            loadTrainers();
        });
    </script>
    @endpush
</x-app-layout>