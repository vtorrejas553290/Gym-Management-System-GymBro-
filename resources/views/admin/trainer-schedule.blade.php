@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">Trainer Schedules Management</h1>
                <p class="text-gray-600 mt-1">Manage member bookings and trainer assignments</p>
            </div>
            <button onclick="openAssignModal()" class="bg-[#0070FF] hover:bg-[#0060DD] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Assign Trainer
            </button>
        </div>

        <!-- Stats Cards - Icon and Title top-left, Values bottom-right -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Sessions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-[#0070FF] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Total Sessions</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="totalSessions" class="text-2xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Scheduled Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Scheduled</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="scheduledCount" class="text-2xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Completed</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="completedCount" class="text-2xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Pending Payment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Pending Payment</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="pendingPaymentCount" class="text-2xl text-gray-900 font-bold">0</h3>
                </div>
            </div>
        </div>

       <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by member, trainer, or session type..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div>
                    <input type="date" id="dateFilter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div>
                    <select id="trainerFilter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent appearance-none cursor-pointer">
                        <option value="All">All Trainers</option>
                    </select>
                </div>
                <div>
                    <select id="statusFilter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent appearance-none cursor-pointer">
                        <option value="All">All Status</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing <span id="resultsCount" class="text-gray-900 font-medium">0</span> sessions
                </p>
                <button id="clearFiltersBtn" class="text-[#0070FF] hover:text-[#005FCC] font-medium text-sm hidden">Clear filters</button>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Trainer</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Session Details</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="schedulesTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Loading schedules...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <span id="paginateFrom">0</span> to <span id="paginateTo">0</span> of <span id="paginateTotal">0</span> sessions
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

<!-- Edit/Assign Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-xl text-gray-900">Assign Trainer to Member</h2>
            <button onclick="closeScheduleModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="scheduleForm" class="p-6 space-y-4">
            <input type="hidden" id="editScheduleId">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Member</label>
                    <select id="memberId" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                        <option value="">Select member</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Trainer</label>
                    <select id="trainerId" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                        <option value="">Select trainer</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Session Type</label>
                <select id="sessionType" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                    <option value="">Select session type</option>
                    <option value="Strength Training">Strength Training</option>
                    <option value="Cardio & HIIT">Cardio & HIIT</option>
                    <option value="Yoga & Pilates">Yoga & Pilates</option>
                    <option value="CrossFit">CrossFit</option>
                    <option value="Functional Training">Functional Training</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="sessionDate" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Time</label>
                    <input type="time" id="sessionTime" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Duration</label>
                    <select id="duration" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                        <option value="30 minutes">30 minutes</option>
                        <option value="45 minutes">45 minutes</option>
                        <option value="1 hour">1 hour</option>
                        <option value="1.5 hours">1.5 hours</option>
                        <option value="2 hours">2 hours</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" id="location" placeholder="e.g., Main Gym Floor - Zone A" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeScheduleModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-[#0070FF] hover:bg-[#0060DD] text-white rounded-lg py-2 transition-colors">
                    Assign Trainer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl text-gray-900">Update Session Status</h2>
            <button onclick="closeStatusModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4" id="statusModalSessionInfo"></p>
            <div class="space-y-3">
                <button onclick="updateSessionStatus('Scheduled')" class="w-full px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-between">
                    <span>Scheduled</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
                <button onclick="updateSessionStatus('Completed')" class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-between">
                    <span>Completed</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
                <button onclick="updateSessionStatus('Cancelled')" class="w-full px-4 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center justify-between">
                    <span>Cancelled</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentEditId = null;
let currentStatusUpdateId = null;
let searchTerm = "";
let filterDate = "";
let filterTrainerId = "All";
let filterStatus = "All";
let allSchedules = [];
let currentPage = 1;
const perPage = 10;

// Load members and trainers on page load
async function loadMembersAndTrainers() {
    try {
        // Load members
        const membersResponse = await fetch('{{ route("admin.members.list") }}');
        const members = await membersResponse.json();
        const memberSelect = document.getElementById('memberId');
        memberSelect.innerHTML = '<option value="">Select member</option>';
        members.forEach(member => {
            memberSelect.innerHTML += `<option value="${member.id}">${member.name}</option>`;
        });

        // Load trainers
        const trainersResponse = await fetch('{{ route("admin.trainers.list") }}');
        const trainers = await trainersResponse.json();
        const trainerSelect = document.getElementById('trainerId');
        const trainerFilter = document.getElementById('trainerFilter');
        trainerSelect.innerHTML = '<option value="">Select trainer</option>';
        trainerFilter.innerHTML = '<option value="All">All Trainers</option>';
        trainers.forEach(trainer => {
            trainerSelect.innerHTML += `<option value="${trainer.id}">${trainer.name}</option>`;
            trainerFilter.innerHTML += `<option value="${trainer.id}">${trainer.name}</option>`;
        });
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
}

async function loadSchedules() {
    try {
        const params = new URLSearchParams({
            search: searchTerm,
            date: filterDate,
            trainer_id: filterTrainerId,
            status: filterStatus
        });

        const response = await fetch(`{{ route("admin.schedules.data") }}?${params}`);
        const data = await response.json();
        
        updateStats(data.stats);
        allSchedules = data.schedules;
        currentPage = 1;
        renderSchedulesTable();
    } catch (error) {
        console.error('Error loading schedules:', error);
        document.getElementById('schedulesTableBody').innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-red-500">
                    Error loading schedules. Please refresh the page.
                </td>
            </tr>
        `;
    }
}

function updateStats(stats) {
    document.getElementById('totalSessions').textContent = stats.total;
    document.getElementById('scheduledCount').textContent = stats.scheduled;
    document.getElementById('completedCount').textContent = stats.completed;
    document.getElementById('pendingPaymentCount').textContent = stats.pendingPayment;
}

function openStatusModal(scheduleId, memberName, trainerName, sessionDate) {
    currentStatusUpdateId = scheduleId;
    document.getElementById('statusModalSessionInfo').innerHTML = `
        <strong>${memberName}</strong> with <strong>${trainerName}</strong><br>
        Session on ${formatDate(sessionDate)}
    `;
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
    currentStatusUpdateId = null;
}

async function updateSessionStatus(newStatus) {
    if (!currentStatusUpdateId) return;
    
    try {
        // Get the actual schedule ID (remove the 'TS' prefix if present)
        let scheduleId = currentStatusUpdateId;
        if (typeof scheduleId === 'string' && scheduleId.startsWith('TS')) {
            scheduleId = scheduleId.replace('TS', '').replace(/^0+/, '');
        }
        
        console.log('Updating status for schedule ID:', scheduleId, 'to:', newStatus);
        
        const response = await fetch(`/admin/schedules/${scheduleId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            alert(`Session status updated to ${newStatus} successfully!`);
            closeStatusModal();
            loadSchedules(); // Reload the table
        } else {
            alert(data.message || "Error updating session status");
        }
    } catch (error) {
        console.error('Error:', error);
        alert("Error updating session status: " + error.message);
    }
}

function renderSchedulesTable() {
    const totalItems = allSchedules.length;
    const totalPages = Math.ceil(totalItems / perPage);
    
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedSchedules = allSchedules.slice(start, end);
    
    const tbody = document.getElementById('schedulesTableBody');
    const resultsCount = document.getElementById('resultsCount');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    resultsCount.textContent = totalItems;

    if (searchTerm || filterDate || filterTrainerId !== "All" || filterStatus !== "All") {
        clearFiltersBtn.classList.remove('hidden');
    } else {
        clearFiltersBtn.classList.add('hidden');
    }
    
    // Update pagination info
    const from = totalItems === 0 ? 0 : start + 1;
    const to = Math.min(end, totalItems);
    document.getElementById('paginateFrom').textContent = from;
    document.getElementById('paginateTo').textContent = to;
    document.getElementById('paginateTotal').textContent = totalItems;
    
    // Update button states
    document.getElementById('prevPageBtn').disabled = currentPage === 1;
    document.getElementById('nextPageBtn').disabled = currentPage === totalPages || totalItems === 0;

    if (paginatedSchedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    No sessions found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = paginatedSchedules.map(schedule => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 font-medium">${schedule.memberName}</p>
                        <p class="text-xs text-gray-500">${schedule.id}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-[#E6F0FF] rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-900">${schedule.trainerName}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-900 font-medium">${schedule.sessionType}</p>
                <p class="text-xs text-gray-500">${schedule.location}</p>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm text-gray-900">${formatDate(schedule.sessionDate)}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">${schedule.sessionTime} • ${schedule.duration}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-medium ${schedule.paymentStatus === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                    ${schedule.paymentStatus}
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-medium ${schedule.status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : schedule.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${schedule.status}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="openEditModal('${schedule.id}')" class="p-2 hover:bg-[#E6F0FF] rounded-lg text-[#0070FF] transition-colors" title="Edit Schedule">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </button>
                    <button onclick="openStatusModal('${schedule.id}', '${schedule.memberName}', '${schedule.trainerName}', '${schedule.sessionDate}')" class="p-2 hover:bg-purple-50 rounded-lg text-purple-600 transition-colors" title="Change Status">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    ${schedule.status === "Scheduled" ? `
                        <button onclick="cancelSession('${schedule.id}')" class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors" title="Cancel Session">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

async function openEditModal(id) {
    try {
        const response = await fetch(`{{ route("admin.schedules.data") }}`);
        const data = await response.json();
        const schedule = data.schedules.find(s => s.id === id);
        
        if (schedule) {
            currentEditId = id.replace('TS', '').replace(/^0+/, '');
            document.getElementById('modalTitle').innerText = "Edit Schedule";
            document.getElementById('editScheduleId').value = id;
            document.getElementById('memberId').value = schedule.memberId;
            document.getElementById('trainerId').value = schedule.trainerId;
            document.getElementById('sessionType').value = schedule.sessionType;
            document.getElementById('sessionDate').value = schedule.sessionDate;
            document.getElementById('sessionTime').value = schedule.sessionTime;
            document.getElementById('duration').value = schedule.duration;
            document.getElementById('location').value = schedule.location;
            document.getElementById('paymentStatus').value = schedule.paymentStatus;
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.getElementById('scheduleModal').classList.add('flex');
        }
    } catch (error) {
        console.error('Error fetching schedule:', error);
        alert('Error loading schedule details');
    }
}

function openAssignModal() {
    currentEditId = null;
    document.getElementById('modalTitle').innerText = "Assign Trainer to Member";
    document.getElementById('scheduleForm').reset();
    document.getElementById('editScheduleId').value = '';
    document.getElementById('memberId').value = '';
    document.getElementById('trainerId').value = '';
    document.getElementById('sessionType').value = '';
    document.getElementById('sessionDate').value = '';
    document.getElementById('sessionTime').value = '';
    document.getElementById('duration').value = '1 hour';
    document.getElementById('location').value = '';
    document.getElementById('paymentStatus').value = 'Pending';
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('flex');
    currentEditId = null;
}

async function cancelSession(id) {
    if (confirm("Are you sure you want to cancel this session?")) {
        try {
            const scheduleId = id.replace('TS', '').replace(/^0+/, '');
            const response = await fetch(`{{ url("admin/schedules") }}/${scheduleId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                alert("Session cancelled successfully");
                loadSchedules();
            } else {
                alert("Error cancelling session");
            }
        } catch (error) {
            console.error('Error:', error);
            alert("Error cancelling session");
        }
    }
}

// Form submission
document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        member_id: document.getElementById('memberId').value,
        trainer_id: document.getElementById('trainerId').value,
        session_type: document.getElementById('sessionType').value,
        session_date: document.getElementById('sessionDate').value,
        session_time: document.getElementById('sessionTime').value,
        duration: document.getElementById('duration').value,
        location: document.getElementById('location').value,
        payment_status: document.getElementById('paymentStatus').value,
    };

    try {
        let url = '{{ route("admin.schedules.store") }}';
        let method = 'POST';
        
        if (currentEditId) {
            url = `{{ url("admin/schedules") }}/${currentEditId}`;
            method = 'PUT';
        }
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        });
        
        if (response.ok) {
            alert(currentEditId ? "Schedule updated successfully" : "Trainer assigned successfully");
            closeScheduleModal();
            loadSchedules();
        } else {
            const error = await response.json();
            alert(error.message || "Error saving schedule");
        }
    } catch (error) {
        console.error('Error:', error);
        alert("Error saving schedule");
    }
});

// Search and filter listeners
document.getElementById('searchInput').addEventListener('input', function(e) {
    searchTerm = e.target.value;
    loadSchedules();
});

document.getElementById('dateFilter').addEventListener('change', function(e) {
    filterDate = e.target.value;
    loadSchedules();
});

document.getElementById('trainerFilter').addEventListener('change', function(e) {
    filterTrainerId = e.target.value;
    loadSchedules();
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterStatus = e.target.value;
    loadSchedules();
});

document.getElementById('clearFiltersBtn').addEventListener('click', function() {
    searchTerm = "";
    filterDate = "";
    filterTrainerId = "All";
    filterStatus = "All";
    document.getElementById('searchInput').value = "";
    document.getElementById('dateFilter').value = "";
    document.getElementById('trainerFilter').value = "All";
    document.getElementById('statusFilter').value = "All";
    loadSchedules();
});

// Pagination listeners
document.getElementById('prevPageBtn').addEventListener('click', function() {
    if (currentPage > 1) {
        currentPage--;
        renderSchedulesTable();
    }
});

document.getElementById('nextPageBtn').addEventListener('click', function() {
    const totalPages = Math.ceil(allSchedules.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderSchedulesTable();
    }
});

// Initial load
loadMembersAndTrainers();
loadSchedules();
</script>
@endsection