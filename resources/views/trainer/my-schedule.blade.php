@extends('layouts.trainer-app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header - Removed Assign Trainer button, changed title -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">My Schedule</h1>
                <p class="text-gray-600 mt-1">View and manage your training sessions</p>
            </div>
        </div>

        <!-- Stats Cards - Icon and Title top-left, Values bottom-right -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Sessions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-[#0070FF] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Total Sessions</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="totalSessions" class="text-3xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Scheduled Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Scheduled</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="scheduledCount" class="text-3xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Completed</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="completedCount" class="text-3xl text-gray-900 font-bold">0</h3>
                </div>
            </div>

            <!-- Pending Payment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Pending Payment</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="pendingPaymentCount" class="text-3xl text-gray-900 font-bold">0</h3>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by member or session type..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="sm:w-48">
                    <input type="date" id="dateFilter" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="sm:w-48">
                    <select id="statusFilter" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                        <option value="">All Status</option>
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

        <!-- Schedules Table - Removed Trainer column -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Session Details</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="schedulesTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
                        Showing <span id="paginateFrom">0</span> to <span id="paginateTo">0</span> of <span id="paginateTotal">0</span> results
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
let currentStatusUpdateId = null;
let searchTerm = "";
let filterDate = "";
let filterStatus = "";
let allSchedules = [];
let currentPage = 1;
const perPage = 5;

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
}

async function loadSchedules() {
    try {
        const params = new URLSearchParams({
            search: searchTerm,
            date: filterDate,
            status: filterStatus
        });

        const response = await fetch(`/trainer/schedules/data?${params}`);
        const data = await response.json();
        
        updateStats(data.stats);
        allSchedules = data.schedules;
        currentPage = 1;
        renderPaginatedTable();
    } catch (error) {
        console.error('Error loading schedules:', error);
        document.getElementById('schedulesTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-red-500">
                    Error loading schedules. Please refresh the page.
                </td>
            </tr>
        `;
    }
}

function renderPaginatedTable() {
    const totalItems = allSchedules.length;
    const totalPages = Math.ceil(totalItems / perPage);
    
    // Ensure current page is within bounds
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedSchedules = allSchedules.slice(start, end);
    
    renderSchedulesTable(paginatedSchedules);
    updatePaginationControls(totalItems, start, end);
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

function updateStats(stats) {
    document.getElementById('totalSessions').textContent = stats.total;
    document.getElementById('scheduledCount').textContent = stats.scheduled;
    document.getElementById('completedCount').textContent = stats.completed;
    document.getElementById('pendingPaymentCount').textContent = stats.pendingPayment;
}

function openStatusModal(scheduleId, memberName, sessionDate) {
    currentStatusUpdateId = scheduleId;
    document.getElementById('statusModalSessionInfo').innerHTML = `
        <strong>${escapeHtml(memberName)}</strong><br>
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
        let scheduleId = currentStatusUpdateId;
        if (typeof scheduleId === 'string' && scheduleId.startsWith('TS')) {
            scheduleId = scheduleId.replace('TS', '').replace(/^0+/, '');
        }
        
        const response = await fetch(`/trainer/schedules/${scheduleId}/status`, {
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
            loadSchedules();
        } else {
            alert(data.message || "Error updating session status");
        }
    } catch (error) {
        console.error('Error:', error);
        alert("Error updating session status: " + error.message);
    }
}

async function cancelSession(id) {
    if (confirm("Are you sure you want to cancel this session?")) {
        try {
            const scheduleId = id.replace('TS', '').replace(/^0+/, '');
            const response = await fetch(`/trainer/schedules/${scheduleId}/cancel`, {
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

function renderSchedulesTable(schedules) {
    const tbody = document.getElementById('schedulesTableBody');
    const resultsCount = document.getElementById('resultsCount');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    resultsCount.textContent = schedules.length;

    if (searchTerm || filterDate || filterStatus) {
        clearFiltersBtn.classList.remove('hidden');
    } else {
        clearFiltersBtn.classList.add('hidden');
    }

    if (schedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    No sessions found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = schedules.map(schedule => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 font-medium">${escapeHtml(schedule.memberName)}</p>
                        <p class="text-xs text-gray-500">${schedule.id}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-900 font-medium">${escapeHtml(schedule.sessionType)}</p>
                <p class="text-xs text-gray-500">${escapeHtml(schedule.location)}</p>
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
                    <button onclick="openStatusModal('${schedule.id}', '${escapeHtml(schedule.memberName)}', '${schedule.sessionDate}')" class="p-2 hover:bg-purple-50 rounded-lg text-purple-600 transition-colors" title="Change Status">
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

// Helper function to escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Search and filter listeners
document.getElementById('searchInput').addEventListener('input', function(e) {
    searchTerm = e.target.value;
    loadSchedules();
});

document.getElementById('dateFilter').addEventListener('change', function(e) {
    filterDate = e.target.value;
    loadSchedules();
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterStatus = e.target.value;
    loadSchedules();
});

document.getElementById('clearFiltersBtn').addEventListener('click', function() {
    searchTerm = "";
    filterDate = "";
    filterStatus = "";
    document.getElementById('searchInput').value = "";
    document.getElementById('dateFilter').value = "";
    document.getElementById('statusFilter').value = "";
    loadSchedules();
});

// Pagination listeners
document.getElementById('prevPageBtn').addEventListener('click', function() {
    if (currentPage > 1) {
        currentPage--;
        renderPaginatedTable();
    }
});

document.getElementById('nextPageBtn').addEventListener('click', function() {
    const totalPages = Math.ceil(allSchedules.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPaginatedTable();
    }
});

// Initial load
loadSchedules();
</script>
@endsection