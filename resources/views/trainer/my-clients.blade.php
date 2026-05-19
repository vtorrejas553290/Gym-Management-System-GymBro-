@extends('layouts.trainer-app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">My Clients</h1>
                <p class="text-gray-600 mt-1">Manage your assigned clients and their progress</p>
            </div>
        </div>

        <!-- Stats Cards - Icon and Title top-left, Values bottom-right -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Clients Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-[#0070FF] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Total Clients</p>
                </div>
                <div class="mt-auto text-right">
                    <p class="text-3xl text-gray-900 font-bold" id="totalClients">0</p>
                </div>
            </div>

            <!-- Active Clients Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-[#00BFA5] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Active Clients</p>
                </div>
                <div class="mt-auto text-right">
                    <p class="text-3xl text-gray-900 font-bold" id="activeClients">0</p>
                </div>
            </div>

            <!-- Inactive Clients Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-gray-400 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Inactive Clients</p>
                </div>
                <div class="mt-auto text-right">
                    <p class="text-3xl text-gray-900 font-bold" id="inactiveClients">0</p>
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
                    <input type="text" id="searchInput" placeholder="Search by name, email, or plan..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="sm:w-48">
                    <select id="statusFilter" class="w-full px-4 py-2 pr-8 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                        <option value="All">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing <span id="resultsCount" class="text-gray-900 font-medium">0</span> clients
                </p>
                <button id="clearFiltersBtn" class="text-[#0070FF] hover:text-[#005FCC] font-medium text-sm hidden">Clear filters</button>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Assigned Plan</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Fitness Goal</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Sessions</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientsTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#0070FF]"></div>
                                <p class="mt-2">Loading clients...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <span id="paginateFrom">0</span> to <span id="paginateTo">0</span> of <span id="paginateTotal">0</span> clients
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

<!-- Client Details Modal -->
<div id="clientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modalClientName" class="text-xl text-gray-900"></h3>
                        <p id="modalClientEmail" class="text-sm text-gray-600 mt-1"></p>
                    </div>
                </div>
                <span id="modalClientStatus" class="px-4 py-2 rounded-full text-sm"></span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Personal Info -->
            <div>
                <h4 class="text-gray-900 font-medium mb-3">Personal Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Member Since</p>
                        <p id="modalStartDate" class="text-gray-900 mt-1"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p id="modalPhone" class="text-gray-900 mt-1"></p>
                    </div>
                </div>
            </div>

            <!-- Fitness Plan -->
            <div>
                <h4 class="text-gray-900 font-medium mb-3">Fitness Plan</h4>
                <div class="bg-[#E6F0FF] border border-[#0070FF] rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p id="modalPlan" class="text-gray-900 font-medium"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <p id="modalGoal" class="text-gray-600"></p>
                    </div>
                </div>
            </div>

            <!-- Session Overview -->
            <div>
                <h4 class="text-gray-900 font-medium mb-3">Session Overview</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Sessions Completed</p>
                        <p id="modalSessions" class="text-2xl text-gray-900 mt-1 font-bold">0</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Start Date</p>
                        <p id="modalJoinDate" class="text-2xl text-gray-900 mt-1 font-bold"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200">
            <button onclick="closeModal()" class="w-full px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let clients = [];
let filteredClients = [];
let currentClient = null;
let searchTerm = "";
let filterStatus = "All";
let currentPage = 1;
const perPage = 5;

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
}

async function loadClients() {
    try {
        const response = await fetch('/trainer/clients/data');
        if (response.ok) {
            const data = await response.json();
            clients = data.clients || [];
            updateStats(data.stats);
            applyFilters();
        } else {
            throw new Error('Failed to load clients');
        }
    } catch (error) {
        console.error('Error loading clients:', error);
        document.getElementById('clientsTableBody').innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-red-500">
                    Error loading clients. Please refresh the page.
                </td>
            </tr>
        `;
    }
}

function applyFilters() {
    filteredClients = clients.filter(client => {
        const matchesSearch = searchTerm === "" || 
            client.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            client.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
            client.plan.toLowerCase().includes(searchTerm.toLowerCase());

        const matchesStatus = filterStatus === "All" || client.status === filterStatus;

        return matchesSearch && matchesStatus;
    });
    
    currentPage = 1;
    renderClientsTable();
}

function updateStats(stats) {
    document.getElementById('totalClients').textContent = stats?.total || 0;
    document.getElementById('activeClients').textContent = stats?.active || 0;
    document.getElementById('inactiveClients').textContent = stats?.inactive || 0;
}

function renderClientsTable() {
    const resultsCount = document.getElementById('resultsCount');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const totalItems = filteredClients.length;
    const totalPages = Math.ceil(totalItems / perPage);
    
    // Ensure current page is within bounds
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedClients = filteredClients.slice(start, end);

    resultsCount.textContent = totalItems;

    if (searchTerm || filterStatus !== "All") {
        clearFiltersBtn.classList.remove('hidden');
    } else {
        clearFiltersBtn.classList.add('hidden');
    }

    updatePaginationControls(totalItems, start, end);

    if (paginatedClients.length === 0) {
        document.getElementById('clientsTableBody').innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    No clients found
                </td>
            </tr>
        `;
        return;
    }

    document.getElementById('clientsTableBody').innerHTML = paginatedClients.map(client => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 font-medium">${escapeHtml(client.name)}</p>
                        <p class="text-xs text-gray-500">Since ${client.startDate}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-600">${escapeHtml(client.email)}</p>
                <p class="text-xs text-gray-500">${escapeHtml(client.phone)}</p>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
                ${escapeHtml(client.plan)}
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
                ${escapeHtml(client.goal)}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                ${client.sessionsCompleted}
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium ${client.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                    ${client.status}
                </span>
            </td>
            <td class="px-6 py-4 text-sm">
                <div class="flex items-center gap-2">
                    <button onclick="viewClientDetails(${client.id})" class="p-2 text-[#0070FF] hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
             </td>
         </tr>
    `).join('');
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

function viewClientDetails(clientId) {
    const client = clients.find(c => c.id === clientId);
    if (client) {
        currentClient = client;
        document.getElementById('modalClientName').innerText = client.name;
        document.getElementById('modalClientEmail').innerText = client.email;
        document.getElementById('modalClientStatus').innerText = client.status;
        document.getElementById('modalClientStatus').className = `px-4 py-2 rounded-full text-sm font-medium ${client.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
        document.getElementById('modalStartDate').innerText = client.startDate;
        document.getElementById('modalPhone').innerText = client.phone;
        document.getElementById('modalPlan').innerText = client.plan;
        document.getElementById('modalGoal').innerText = client.goal;
        document.getElementById('modalSessions').innerText = client.sessionsCompleted;
        document.getElementById('modalJoinDate').innerText = client.startDate;
        
        document.getElementById('clientModal').classList.remove('hidden');
        document.getElementById('clientModal').classList.add('flex');
    }
}

function closeModal() {
    document.getElementById('clientModal').classList.add('hidden');
    document.getElementById('clientModal').classList.remove('flex');
    currentClient = null;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Search and filter listeners
document.getElementById('searchInput').addEventListener('input', function(e) {
    searchTerm = e.target.value;
    applyFilters();
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterStatus = e.target.value;
    applyFilters();
});

document.getElementById('clearFiltersBtn').addEventListener('click', function() {
    searchTerm = "";
    filterStatus = "All";
    document.getElementById('searchInput').value = "";
    document.getElementById('statusFilter').value = "All";
    applyFilters();
});

// Pagination listeners
document.getElementById('prevPageBtn').addEventListener('click', function() {
    if (currentPage > 1) {
        currentPage--;
        renderClientsTable();
    }
});

document.getElementById('nextPageBtn').addEventListener('click', function() {
    const totalPages = Math.ceil(filteredClients.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderClientsTable();
    }
});

// Initial load
loadClients();
</script>
@endsection