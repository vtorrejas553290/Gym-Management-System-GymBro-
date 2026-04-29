@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl text-gray-900 mb-2">Attendance</h1>
            <p class="text-gray-600">Track and manage member attendance across all training sessions</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Records Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="bg-[#E6F0FF] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Records</p>
                        <p id="totalRecords" class="text-2xl text-gray-900 font-bold">0</p>
                    </div>
                </div>
            </div>

            <!-- Present Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Present</p>
                        <p id="totalPresent" class="text-2xl text-gray-900 font-bold">0</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Rate Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="bg-[#E6F0FF] p-3 rounded-lg">
                        <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Attendance Rate</p>
                        <p id="attendanceRate" class="text-2xl text-gray-900 font-bold">0%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Search Bar -->
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by member name, session, or trainer..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <select id="statusFilter" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    <option value="All">All Status</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>

                <!-- Session Filter -->
                <select id="sessionFilter" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    <option value="All">All Sessions</option>
                </select>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Member</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Session</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Trainer</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Date</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Time</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody" class="divide-y divide-gray-100">
                        <!-- Attendance records will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Attendance Data
    let attendanceData = [
        {
            id: "1",
            memberName: "John Smith",
            memberEmail: "john@example.com",
            sessionName: "Personal Training - Strength",
            trainerName: "Mike Chen",
            date: "Mar 30, 2026",
            time: "8:00 AM",
            status: "Present",
        },
        {
            id: "2",
            memberName: "Sarah Johnson",
            memberEmail: "sarah@example.com",
            sessionName: "HIIT Training",
            trainerName: "Alex Martinez",
            date: "Mar 30, 2026",
            time: "6:00 PM",
            status: "Present",
        },
        {
            id: "3",
            memberName: "Mike Wilson",
            memberEmail: "mike@example.com",
            sessionName: "Core & Abs Workout",
            trainerName: "Jessica Lee",
            date: "Mar 30, 2026",
            time: "5:00 PM",
            status: "Absent",
        },
        {
            id: "4",
            memberName: "Emily Davis",
            memberEmail: "emily@example.com",
            sessionName: "Personal Training - Strength",
            trainerName: "Mike Chen",
            date: "Mar 30, 2026",
            time: "8:00 AM",
            status: "Present",
        },
        {
            id: "5",
            memberName: "David Brown",
            memberEmail: "david@example.com",
            sessionName: "Strength & Conditioning",
            trainerName: "Chris Johnson",
            date: "Mar 29, 2026",
            time: "10:00 AM",
            status: "Present",
        },
        {
            id: "6",
            memberName: "Lisa Anderson",
            memberEmail: "lisa@example.com",
            sessionName: "HIIT Training",
            trainerName: "Alex Martinez",
            date: "Mar 29, 2026",
            time: "6:00 PM",
            status: "Absent",
        },
        {
            id: "7",
            memberName: "Robert Taylor",
            memberEmail: "robert@example.com",
            sessionName: "Core & Abs Workout",
            trainerName: "Jessica Lee",
            date: "Mar 29, 2026",
            time: "5:00 PM",
            status: "Present",
        },
        {
            id: "8",
            memberName: "Jennifer White",
            memberEmail: "jennifer@example.com",
            sessionName: "Yoga & Flexibility",
            trainerName: "Emma Wilson",
            date: "Mar 28, 2026",
            time: "8:00 AM",
            status: "Present",
        },
    ];

    let searchQuery = "";
    let filterStatus = "All";
    let filterSession = "All";

    // Get unique session names
    function getUniqueSessions() {
        return [...new Set(attendanceData.map(record => record.sessionName))];
    }

    function updateSessionFilter() {
        const sessionFilter = document.getElementById('sessionFilter');
        const uniqueSessions = getUniqueSessions();
        
        sessionFilter.innerHTML = '<option value="All">All Sessions</option>' + 
            uniqueSessions.map(session => `<option value="${session}">${session}</option>`).join('');
    }

    function updateStats() {
        const totalRecords = attendanceData.length;
        const totalPresent = attendanceData.filter(r => r.status === "Present").length;
        const attendanceRate = Math.round((totalPresent / totalRecords) * 100);
        
        document.getElementById('totalRecords').textContent = totalRecords;
        document.getElementById('totalPresent').textContent = totalPresent;
        document.getElementById('attendanceRate').textContent = `${attendanceRate}%`;
    }

    function renderAttendanceTable() {
        const filtered = attendanceData.filter(record => {
            const matchesSearch = searchQuery === "" ||
                record.memberName.toLowerCase().includes(searchQuery.toLowerCase()) ||
                record.sessionName.toLowerCase().includes(searchQuery.toLowerCase()) ||
                record.trainerName.toLowerCase().includes(searchQuery.toLowerCase());
            
            const matchesStatus = filterStatus === "All" || record.status === filterStatus;
            const matchesSession = filterSession === "All" || record.sessionName === filterSession;
            
            return matchesSearch && matchesStatus && matchesSession;
        });

        const tbody = document.getElementById('attendanceTableBody');
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No attendance records found
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = filtered.map(record => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">${record.memberName}</p>
                        <p class="text-xs text-gray-500">${record.memberEmail}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-900">${record.sessionName}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-900">${record.trainerName}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-500">${record.date}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-500">${record.time}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium ${record.status === 'Present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                        ${record.status === 'Present' ? `
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        ` : `
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        `}
                        ${record.status}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    // Event Listeners
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchQuery = e.target.value;
        renderAttendanceTable();
    });

    document.getElementById('statusFilter').addEventListener('change', function(e) {
        filterStatus = e.target.value;
        renderAttendanceTable();
    });

    document.getElementById('sessionFilter').addEventListener('change', function(e) {
        filterSession = e.target.value;
        renderAttendanceTable();
    });

    // Initialize
    updateSessionFilter();
    updateStats();
    renderAttendanceTable();
</script>
@endsection