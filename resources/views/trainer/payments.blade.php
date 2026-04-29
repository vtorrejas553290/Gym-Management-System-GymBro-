@extends('layouts.trainer-app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">Payments</h1>
                <p class="text-gray-600 mt-1">View and manage payments for your training sessions</p>
            </div>
        </div>

        <!-- Summary Cards - Icon and Title top-left, Values bottom-right -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Paid Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Total Paid</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="totalPaidAmount" class="text-3xl text-gray-900 font-bold">₱0</h3>
                    <p id="paidCount" class="text-xs text-green-600 mt-1">0 payments</p>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Pending</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="totalPendingAmount" class="text-3xl text-gray-900 font-bold">₱0</h3>
                    <p id="pendingCount" class="text-xs text-yellow-600 mt-1">0 payments</p>
                </div>
            </div>

            <!-- Overdue Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Overdue</p>
                </div>
                <div class="mt-auto text-right">
                    <h3 id="totalOverdueAmount" class="text-3xl text-gray-900 font-bold">₱0</h3>
                    <p id="overdueCount" class="text-xs text-red-600 mt-1">0 payments</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by member name or payment ID..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3399FF] focus:border-transparent">
                </div>
                <div class="sm:w-48">
                    <select id="statusFilter" class="w-full px-4 py-2 pr-8 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3399FF] focus:border-transparent">
                        <option value="All">All Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div style="overflow-x: auto; overflow-y: hidden; width: 100%;">
                <table style="min-width: 1200px; width: 100%; border-collapse: collapse;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Payment ID</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Member</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Type</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Amount</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Date</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Method</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Session Date</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">GCash #</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Ref #</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Proof</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Status</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider" style="white-space: nowrap;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#3399FF]"></div>
                                <p class="mt-2">Loading payments...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalRecords">0</span> payments
                    </div>
                    <div class="flex gap-2">
                        <button onclick="previousPage()" id="prevBtn" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Previous
                        </button>
                        <span id="pageInfo" class="px-3 py-2 text-sm text-gray-600">Page 1</span>
                        <button onclick="nextPage()" id="nextBtn" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
<!-- Edit Payment Modal (Status & Message) - Same as admin -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-xl text-gray-900">Update Payment Status</h2>
            <button onclick="closePaymentModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="paymentForm" class="p-6 space-y-4">
            <input type="hidden" id="editPaymentId">
            
            <!-- Display Only Fields (Read-only) -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Payment ID:</span>
                    <span id="viewPaymentId" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Member:</span>
                    <span id="viewMemberName" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Type:</span>
                    <span id="viewType" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Amount:</span>
                    <span id="viewAmount" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Payment Date:</span>
                    <span id="viewDate" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Method:</span>
                    <span id="viewMethod" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Session Date:</span>
                    <span id="viewSessionDate" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">GCash Number:</span>
                    <span id="viewGcashNumber" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Reference Number:</span>
                    <span id="viewReferenceNumber" class="text-sm text-gray-900 font-medium"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Proof of Payment:</span>
                    <span id="viewProofImage" class="text-sm text-gray-900"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Details:</span>
                    <span id="viewDetails" class="text-sm text-gray-900 font-medium"></span>
                </div>
            </div>

            <!-- Editable Fields -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                <select id="paymentStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3399FF] focus:border-transparent" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Overdue">Overdue</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Message to Member</label>
                <textarea id="adminMessage" rows="3" placeholder="Add a message for the member about this payment..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3399FF] focus:border-transparent"></textarea>
                <p class="text-xs text-gray-500">This message will be visible to the member</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closePaymentModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-[#3399FF] hover:bg-[#2288EE] text-white rounded-lg py-2 transition-colors">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let payments = [];
    let currentEditId = null;
    let searchTerm = "";
    let statusFilter = "All";
    
    // Pagination variables
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredPayments = [];

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
    }

    function getStatusColor(status) {
        switch (status) {
            case "Paid": return "bg-green-100 text-green-800";
            case "Pending": return "bg-yellow-100 text-yellow-800";
            case "Overdue": return "bg-red-100 text-red-800";
            default: return "bg-gray-100 text-gray-800";
        }
    }

    // Load trainer payments from database (only session-related payments)
    async function loadPayments() {
        try {
            const response = await fetch('/trainer/payments/data');
            if (response.ok) {
                const data = await response.json();
                payments = data;
                updateSummary();
                applyFilters();
            } else {
                throw new Error('Failed to load payments');
            }
        } catch (error) {
            console.error('Error loading payments:', error);
            document.getElementById('paymentsTableBody').innerHTML = `
                <tr>
                    <td colspan="12" class="px-6 py-12 text-center text-red-500">
                        Error loading payments. Please refresh the page.
                    </td>
                </tr>
            `;
        }
    }

    function applyFilters() {
        filteredPayments = payments.filter(payment => {
            const matchesSearch = searchTerm === "" ||
                (payment.member_name && payment.member_name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (payment.payment_id && payment.payment_id.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (payment.reference_number && payment.reference_number.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (payment.gcash_number && payment.gcash_number.toLowerCase().includes(searchTerm.toLowerCase()));
            const matchesStatus = statusFilter === "All" || payment.status === statusFilter;
            return matchesSearch && matchesStatus;
        });
        
        currentPage = 1;
        renderPaymentsTable();
    }

    function updateSummary() {
        const paid = payments.filter(p => p.status === "Paid");
        const pending = payments.filter(p => p.status === "Pending");
        const overdue = payments.filter(p => p.status === "Overdue");
        
        const totalPaid = paid.reduce((sum, p) => sum + parseFloat(p.amount), 0);
        const totalPending = pending.reduce((sum, p) => sum + parseFloat(p.amount), 0);
        const totalOverdue = overdue.reduce((sum, p) => sum + parseFloat(p.amount), 0);
        
        document.getElementById('totalPaidAmount').innerHTML = `₱${totalPaid.toLocaleString()}`;
        document.getElementById('totalPendingAmount').innerHTML = `₱${totalPending.toLocaleString()}`;
        document.getElementById('totalOverdueAmount').innerHTML = `₱${totalOverdue.toLocaleString()}`;
        
        document.getElementById('paidCount').innerHTML = `${paid.length} payments`;
        document.getElementById('pendingCount').innerHTML = `${pending.length} payments`;
        document.getElementById('overdueCount').innerHTML = `${overdue.length} payments`;
    }

    function renderPaymentsTable() {
        const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedPayments = filteredPayments.slice(startIndex, endIndex);
        
        const tbody = document.getElementById('paymentsTableBody');
        
        if (paginatedPayments.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                        No payments found for your sessions
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedPayments.map(payment => {
                const statusColor = getStatusColor(payment.status);
                const proofImageHtml = payment.proof_image ? 
                    `<a href="${payment.proof_image}" target="_blank" class="text-[#3399FF] hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        View
                    </a>` : '-';
                return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(payment.payment_id)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(payment.member_name)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(payment.type)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">₱${parseFloat(payment.amount).toLocaleString()}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(payment.payment_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(payment.method)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${payment.session_date ? formatDate(payment.session_date) : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${payment.gcash_number || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${payment.reference_number || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${proofImageHtml}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium ${statusColor}">
                            ${payment.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editPayment(${payment.id})" class="p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors" title="Edit Payment">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                    </td>
                 </tr>
            `}).join('');
        }
        
        // Update pagination info
        const startRecord = filteredPayments.length === 0 ? 0 : startIndex + 1;
        const endRecord = Math.min(endIndex, filteredPayments.length);
        document.getElementById('showingStart').innerText = startRecord;
        document.getElementById('showingEnd').innerText = endRecord;
        document.getElementById('totalRecords').innerText = filteredPayments.length;
        document.getElementById('pageInfo').innerHTML = `Page ${currentPage} of ${totalPages || 1}`;
        
        document.getElementById('prevBtn').disabled = currentPage === 1;
        document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;
    }
    
    function previousPage() {
        if (currentPage > 1) {
            currentPage--;
            renderPaymentsTable();
        }
    }
    
    function nextPage() {
        const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderPaymentsTable();
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function editPayment(id) {
        const payment = payments.find(p => p.id === id);
        if (payment) {
            currentEditId = id;
            
            document.getElementById('modalTitle').innerText = "Update Payment Status";
            document.getElementById('editPaymentId').value = payment.id;
            document.getElementById('viewPaymentId').innerText = payment.payment_id;
            document.getElementById('viewMemberName').innerText = payment.member_name;
            document.getElementById('viewType').innerText = payment.type;
            document.getElementById('viewAmount').innerText = `₱${parseFloat(payment.amount).toLocaleString()}`;
            document.getElementById('viewDate').innerText = formatDate(payment.payment_date);
            document.getElementById('viewMethod').innerText = payment.method;
            document.getElementById('viewSessionDate').innerText = payment.session_date ? formatDate(payment.session_date) : '-';
            document.getElementById('viewGcashNumber').innerText = payment.gcash_number || '-';
            document.getElementById('viewReferenceNumber').innerText = payment.reference_number || '-';
            document.getElementById('viewDetails').innerText = payment.details || '-';
            
            // Add proof image link
            if (payment.proof_image) {
                document.getElementById('viewProofImage').innerHTML = `<a href="${payment.proof_image}" target="_blank" class="text-[#3399FF] hover:underline flex items-center gap-1">View Proof <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>`;
            } else {
                document.getElementById('viewProofImage').innerHTML = '-';
            }
            
            document.getElementById('paymentStatus').value = payment.status;
            document.getElementById('adminMessage').value = payment.admin_message || '';
            
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
        document.body.style.overflow = '';
        currentEditId = null;
    }

    // Form submission - Update status and admin message
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            status: document.getElementById('paymentStatus').value,
            admin_message: document.getElementById('adminMessage').value,
        };

        try {
            const response = await fetch(`/trainer/payments/${currentEditId}/update`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });
            
            if (response.ok) {
                closePaymentModal();
                await loadPayments();
                alert('Payment updated successfully!');
            } else {
                const error = await response.json();
                alert(error.message || 'Error updating payment');
            }
        } catch (error) {
            console.error('Error updating payment:', error);
            alert('Error updating payment');
        }
    });

    // Search and filter listeners
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value;
        applyFilters();
    });

    document.getElementById('statusFilter').addEventListener('change', function(e) {
        statusFilter = e.target.value;
        applyFilters();
    });

    // Initial load
    loadPayments();

    // Make functions global
    window.editPayment = editPayment;
    window.closePaymentModal = closePaymentModal;
    window.previousPage = previousPage;
    window.nextPage = nextPage;
</script>
@endsection