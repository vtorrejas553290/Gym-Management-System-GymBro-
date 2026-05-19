@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">Trainers</h1>
                <p class="text-gray-600 mt-1">Manage your gym trainers and their specializations</p>
            </div>
            <button onclick="openTrainerModal()" class="bg-[#0070FF] hover:bg-[#0060DD] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Trainer
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by name, email, or specialization..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="lg:w-64">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <select id="specializationFilter" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent appearance-none cursor-pointer">
                            <option value="All">All Specializations</option>
                            <option value="Strength Training">Strength Training</option>
                            <option value="Cardio & HIIT">Cardio & HIIT</option>
                            <option value="Yoga & Pilates">Yoga & Pilates</option>
                            <option value="CrossFit">CrossFit</option>
                            <option value="Personal Training">Personal Training</option>
                            <option value="Nutrition & Wellness">Nutrition & Wellness</option>
                            <option value="Sports Performance">Sports Performance</option>
                        </select>
                    </div>
                </div>
                <div class="lg:w-48">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <select id="statusFilter" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent appearance-none cursor-pointer">
                            <option value="All">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm">
                <p class="text-gray-600">
                    Showing <span id="resultsCount" class="text-gray-900 font-medium">0</span> trainers
                </p>
                <button id="clearFiltersBtn" class="text-[#0070FF] hover:text-[#005FCC] font-medium hidden">Clear filters</button>
            </div>
        </div>

        <!-- Trainers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Trainer</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Specialization</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Experience</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Hourly Rate</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="trainersTableBody" class="divide-y divide-gray-200">
                        <!-- Trainers will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <span id="paginateFrom">0</span> to <span id="paginateTo">0</span> of <span id="paginateTotal">0</span> trainers
                    </div>
                    <div class="flex gap-2">
                        <button id="prevPageBtn" class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                        <button id="nextPageBtn" class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Next
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Trainer Modal -->
<div id="trainerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-xl text-gray-900">Add New Trainer</h2>
            <button onclick="closeTrainerModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="trainerForm" class="p-6 space-y-4">
            <input type="hidden" id="editTrainerId">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="trainerFirstName" placeholder="Enter first name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="trainerLastName" placeholder="Enter last name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                <input type="text" id="trainerMiddleName" placeholder="Enter middle name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="trainerEmail" placeholder="Enter email" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="trainerPhone" placeholder="Enter phone number" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Specialization</label>
                <select id="trainerSpecialization" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                    <option value="">Select specialization</option>
                    <option value="Strength Training">Strength Training</option>
                    <option value="Cardio & HIIT">Cardio & HIIT</option>
                    <option value="Yoga & Pilates">Yoga & Pilates</option>
                    <option value="CrossFit">CrossFit</option>
                    <option value="Personal Training">Personal Training</option>
                    <option value="Nutrition & Wellness">Nutrition & Wellness</option>
                    <option value="Sports Performance">Sports Performance</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Experience (years)</label>
                    <input type="number" id="trainerExperience" placeholder="5" required min="0" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Hourly Rate (₱)</label>
                    <input type="number" id="trainerRate" placeholder="800" required min="0" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="trainerPassword" placeholder="Enter password" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    <p class="text-xs text-gray-500">Leave blank to keep current password</p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="trainerPasswordConfirmation" placeholder="Confirm password" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="trainerStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeTrainerModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-[#0070FF] hover:bg-[#0060DD] text-white rounded-lg py-2 transition-colors">
                    Add Trainer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Rate Modal -->
<div id="rateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="bg-[#E6F0FF] p-2 rounded-lg">
                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl text-gray-900">Edit Hourly Rate</h2>
                    <p id="rateTrainerName" class="text-sm text-gray-600"></p>
                </div>
            </div>
            <button onclick="closeRateModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Current Rate</p>
                        <p id="currentRate" class="text-2xl text-gray-900 font-medium"></p>
                    </div>
                    <div class="text-gray-400">→</div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">New Rate</p>
                        <p id="newRateDisplay" class="text-2xl text-[#0070FF] font-medium"></p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">New Hourly Rate (₱)</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <input type="number" id="newRateInput" placeholder="Enter new rate" required min="0" step="50" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-lg">
                    </div>
                    <p class="text-xs text-gray-500">Recommended range: ₱500 - ₱1,500 per hour</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button onclick="closeRateModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-3 transition-colors">
                    Cancel
                </button>
                <button id="saveRateBtn" class="flex-1 bg-[#0070FF] hover:bg-[#0060DD] text-white rounded-lg py-3 transition-colors">
                    Save Rate
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let currentEditingId = null;
    let currentRateTrainerId = null;
    let searchTerm = "";
    let selectedSpecialization = "All";
    let selectedStatus = "All";
    let filteredTrainers = [];
    let currentPage = 1;
    const perPage = 10;
    let updatedRateId = null;

    // Load trainers from database
    async function loadTrainers() {
        try {
            const response = await fetch('/admin/trainers/data');
            const data = await response.json();
            window.trainers = data;
            applyFilters();
        } catch (error) {
            console.error('Error loading trainers:', error);
        }
    }

    function applyFilters() {
        filteredTrainers = window.trainers.filter(trainer => {
            const fullName = `${trainer.first_name} ${trainer.middle_name ? trainer.middle_name + ' ' : ''}${trainer.last_name}`;
            const matchesSearch = searchTerm === "" ||
                fullName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                trainer.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                trainer.specialization.toLowerCase().includes(searchTerm.toLowerCase());

            const matchesSpecialization = selectedSpecialization === "All" || trainer.specialization === selectedSpecialization;
            
            const trainerStatus = trainer.status || 'Active';
            const matchesStatus = selectedStatus === "All" || trainerStatus === selectedStatus;

            return matchesSearch && matchesSpecialization && matchesStatus;
        });
        
        currentPage = 1;
        renderTrainersTable();
    }

    function renderTrainersTable() {
        const resultsCount = document.getElementById('resultsCount');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const totalItems = filteredTrainers.length;
        const totalPages = Math.ceil(totalItems / perPage);
        
        // Ensure current page is within bounds
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const paginatedTrainers = filteredTrainers.slice(start, end);

        resultsCount.textContent = totalItems;

        if (searchTerm || selectedSpecialization !== "All" || selectedStatus !== "All") {
            clearFiltersBtn.classList.remove('hidden');
        } else {
            clearFiltersBtn.classList.add('hidden');
        }

        updatePaginationControls(totalItems, start, end);

        const tbody = document.getElementById('trainersTableBody');

        if (paginatedTrainers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        No trainers found
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = paginatedTrainers.map(trainer => {
            const fullName = `${trainer.first_name} ${trainer.middle_name ? trainer.middle_name + ' ' : ''}${trainer.last_name}`;
            const trainerStatus = trainer.status || 'Active';
            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium">${escapeHtml(fullName)}</p>
                                <p class="text-xs text-gray-500">ID: ${trainer.id}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(trainer.email)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(trainer.phone)}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#E6F0FF] text-[#0070FF] rounded-lg text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${escapeHtml(trainer.specialization)}
                        </span>
                     </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${trainer.experience} ${trainer.experience === 1 ? 'year' : 'years'}
                     </td>
                    <td class="px-6 py-4">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-all ${updatedRateId === trainer.id ? 'bg-green-100 border-2 border-green-500 animate-pulse' : 'bg-[#E6F0FF] border border-[#0070FF]/20'}">
                            <svg class="w-4 h-4 ${updatedRateId === trainer.id ? 'text-green-700' : 'text-[#0070FF]'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-lg font-medium ${updatedRateId === trainer.id ? 'text-green-900' : 'text-gray-900'}">₱${trainer.hourly_rate.toLocaleString()}</span>
                            <span class="text-xs text-gray-600">/hr</span>
                        </div>
                     </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-medium ${trainerStatus === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${trainerStatus}
                        </span>
                     </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <button onclick="openRateModal(${trainer.id})" class="p-2 hover:bg-[#E6F0FF] rounded-lg text-[#0070FF] transition-colors group" title="Edit Rate">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            <button onclick="editTrainer(${trainer.id})" class="p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors" title="Edit Trainer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteTrainer(${trainer.id})" class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors" title="Delete Trainer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                     </td>
                  </tr>
            `;
        }).join('');
    }

    function updatePaginationControls(totalItems, start, end) {
        const from = totalItems === 0 ? 0 : start + 1;
        const to = Math.min(end, totalItems);
        
        document.getElementById('paginateFrom').textContent = from;
        document.getElementById('paginateTo').textContent = to;
        document.getElementById('paginateTotal').textContent = totalItems;
        
        const totalPages = Math.ceil(totalItems / perPage);
        document.getElementById('prevPageBtn').disabled = currentPage === 1;
        document.getElementById('nextPageBtn').disabled = currentPage === totalPages || totalItems === 0;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function openTrainerModal() {
        currentEditingId = null;
        document.getElementById('modalTitle').innerText = 'Add New Trainer';
        document.getElementById('trainerForm').reset();
        document.getElementById('editTrainerId').value = '';
        document.getElementById('trainerFirstName').value = '';
        document.getElementById('trainerMiddleName').value = '';
        document.getElementById('trainerLastName').value = '';
        document.getElementById('trainerEmail').value = '';
        document.getElementById('trainerPhone').value = '';
        document.getElementById('trainerSpecialization').value = '';
        document.getElementById('trainerExperience').value = '';
        document.getElementById('trainerRate').value = '';
        document.getElementById('trainerPassword').value = '';
        document.getElementById('trainerPasswordConfirmation').value = '';
        document.getElementById('trainerStatus').value = 'Active';
        document.getElementById('trainerModal').classList.remove('hidden');
        document.getElementById('trainerModal').classList.add('flex');
    }

    function closeTrainerModal() {
        document.getElementById('trainerModal').classList.add('hidden');
        document.getElementById('trainerModal').classList.remove('flex');
        currentEditingId = null;
    }

    function editTrainer(id) {
        const trainer = window.trainers.find(t => t.id === id);
        if (trainer) {
            currentEditingId = id;
            document.getElementById('modalTitle').innerText = 'Edit Trainer';
            document.getElementById('editTrainerId').value = trainer.id;
            document.getElementById('trainerFirstName').value = trainer.first_name;
            document.getElementById('trainerMiddleName').value = trainer.middle_name || '';
            document.getElementById('trainerLastName').value = trainer.last_name;
            document.getElementById('trainerEmail').value = trainer.email;
            document.getElementById('trainerPhone').value = trainer.phone;
            document.getElementById('trainerSpecialization').value = trainer.specialization;
            document.getElementById('trainerExperience').value = trainer.experience;
            document.getElementById('trainerRate').value = trainer.hourly_rate;
            document.getElementById('trainerStatus').value = trainer.status || 'Active';
            document.getElementById('trainerPassword').value = '';
            document.getElementById('trainerPasswordConfirmation').value = '';
            document.getElementById('trainerModal').classList.remove('hidden');
            document.getElementById('trainerModal').classList.add('flex');
        }
    }

    async function deleteTrainer(id) {
        if (confirm('Are you sure you want to delete this trainer?')) {
            try {
                const response = await fetch(`/admin/trainers/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                if (response.ok) {
                    await loadTrainers();
                    alert('Trainer deleted successfully');
                } else {
                    alert('Error deleting trainer');
                }
            } catch (error) {
                console.error('Error deleting trainer:', error);
                alert('Error deleting trainer');
            }
        }
    }

    function openRateModal(id) {
        const trainer = window.trainers.find(t => t.id === id);
        if (trainer) {
            currentRateTrainerId = id;
            const fullName = `${trainer.first_name} ${trainer.middle_name ? trainer.middle_name + ' ' : ''}${trainer.last_name}`;
            document.getElementById('rateTrainerName').innerText = fullName;
            document.getElementById('currentRate').innerHTML = `₱${trainer.hourly_rate.toLocaleString()}`;
            document.getElementById('newRateDisplay').innerHTML = `₱${trainer.hourly_rate.toLocaleString()}`;
            document.getElementById('newRateInput').value = trainer.hourly_rate;
            document.getElementById('rateModal').classList.remove('hidden');
            document.getElementById('rateModal').classList.add('flex');
            
            const newRateInput = document.getElementById('newRateInput');
            const newRateDisplay = document.getElementById('newRateDisplay');
            const saveBtn = document.getElementById('saveRateBtn');
            
            newRateInput.oninput = function() {
                const newRate = parseInt(this.value) || 0;
                newRateDisplay.innerHTML = `₱${newRate.toLocaleString()}`;
                if (newRate === trainer.hourly_rate || newRate <= 0) {
                    saveBtn.disabled = true;
                    saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            };
            
            saveBtn.onclick = async function() {
                const newRate = parseInt(newRateInput.value);
                if (newRate && newRate !== trainer.hourly_rate && newRate > 0) {
                    try {
                        const response = await fetch(`/admin/trainers/${id}/rate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ hourly_rate: newRate }),
                        });
                        
                        if (response.ok) {
                            updatedRateId = id;
                            await loadTrainers();
                            setTimeout(() => {
                                updatedRateId = null;
                                renderTrainersTable();
                            }, 2000);
                            closeRateModal();
                            alert(`Hourly rate updated to ₱${newRate}`);
                        } else {
                            alert('Error updating rate');
                        }
                    } catch (error) {
                        console.error('Error updating rate:', error);
                        alert('Error updating rate');
                    }
                }
            };
        }
    }

    function closeRateModal() {
        document.getElementById('rateModal').classList.add('hidden');
        document.getElementById('rateModal').classList.remove('flex');
        currentRateTrainerId = null;
    }

    // Handle form submission
    document.getElementById('trainerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const password = document.getElementById('trainerPassword').value;
        const passwordConfirmation = document.getElementById('trainerPasswordConfirmation').value;
        
        if (password !== passwordConfirmation) {
            alert('Password and Confirm Password do not match');
            return;
        }
        
        const formData = {
            first_name: document.getElementById('trainerFirstName').value,
            middle_name: document.getElementById('trainerMiddleName').value || null,
            last_name: document.getElementById('trainerLastName').value,
            email: document.getElementById('trainerEmail').value,
            phone: document.getElementById('trainerPhone').value,
            specialization: document.getElementById('trainerSpecialization').value,
            experience: parseInt(document.getElementById('trainerExperience').value) || 0,
            hourly_rate: parseFloat(document.getElementById('trainerRate').value) || 0,
            status: document.getElementById('trainerStatus').value,
        };
        
        if (password) {
            formData.password = password;
        }

        try {
            let response;
            if (currentEditingId) {
                response = await fetch(`/admin/trainers/${currentEditingId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
            } else {
                if (!password) {
                    alert('Password is required for new trainers');
                    return;
                }
                response = await fetch('/admin/trainers', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
            }
            
            if (response.ok) {
                closeTrainerModal();
                await loadTrainers();
                alert(currentEditingId ? 'Trainer updated successfully!' : 'Trainer added successfully!');
            } else {
                const error = await response.json();
                alert(error.message || 'Error saving trainer');
            }
        } catch (error) {
            console.error('Error saving trainer:', error);
            alert('Error saving trainer');
        }
    });

    // Search and filter listeners
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value;
        applyFilters();
    });

    document.getElementById('specializationFilter').addEventListener('change', function(e) {
        selectedSpecialization = e.target.value;
        applyFilters();
    });

    document.getElementById('statusFilter').addEventListener('change', function(e) {
        selectedStatus = e.target.value;
        applyFilters();
    });

    document.getElementById('clearFiltersBtn').addEventListener('click', function() {
        searchTerm = "";
        selectedSpecialization = "All";
        selectedStatus = "All";
        document.getElementById('searchInput').value = "";
        document.getElementById('specializationFilter').value = "All";
        document.getElementById('statusFilter').value = "All";
        applyFilters();
    });

    // Pagination listeners
    document.getElementById('prevPageBtn').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            renderTrainersTable();
        }
    });

    document.getElementById('nextPageBtn').addEventListener('click', function() {
        const totalPages = Math.ceil(filteredTrainers.length / perPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderTrainersTable();
        }
    });

    // Initial load
    loadTrainers();
</script>
@endsection