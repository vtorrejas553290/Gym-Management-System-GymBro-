<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="payments-root" class="space-y-6">
                <!-- Loading indicator -->
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0070FF]"></div>
                    <p class="mt-2 text-gray-600">Loading payments...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        let allPayments = [];
        let paymentHistory = [];
        let pendingPaymentsList = [];
        let isPaymentDialogOpen = false;
        let isProcessing = false;
        let gcashNumber = "";
        let referenceNumber = "";
        let proofFile = null;
        let selectedPayment = null;
        let filterStatus = "All";
        let searchQuery = "";
        let showFilters = false;
        let pendingTrainerSessionData = null;
        
        // Pagination variables
        let pendingCurrentPage = 1;
        const pendingItemsPerPage = 2;
        let historyCurrentPage = 1;
        const historyItemsPerPage = 5;

        // GCash Account Details
        const gcashAccountName = "GymBro Fitness Center";
        const gcashAccountNumber = "09123456789";

        async function checkAndCreatePendingPayment() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'pay_trainer') {
                const pendingSession = localStorage.getItem("pendingSession");
                if (pendingSession) {
                    const sessionData = JSON.parse(pendingSession);
                    pendingTrainerSessionData = sessionData;
                    console.log('Pending trainer session detected:', sessionData);
                    
                    try {
                        const response = await fetch('/member/payments', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                type: 'Trainer Session',
                                amount: sessionData.amount,
                                details: `Training session with ${sessionData.trainer_name} - ${sessionData.session_type}`,
                                payment_date: new Date().toISOString().split('T')[0],
                                method: 'GCash',
                                status: 'Pending',
                                schedule_id: sessionData.schedule_id
                            })
                        });
                        
                        if (response.ok) {
                            const newPayment = await response.json();
                            console.log('Payment record created with PENDING status and schedule_id:', newPayment);
                            await loadPayments();
                        } else {
                            await loadPayments();
                        }
                    } catch (error) {
                        console.error('Error creating payment:', error);
                        await loadPayments();
                    }
                } else {
                    await loadPayments();
                }
            } else {
                await loadPayments();
            }
        }

        // Load payments from database
        async function loadPayments() {
            try {
                console.log('Loading payments...');
                const response = await fetch('/member/payments', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load payments');
                }
                const data = await response.json();
                
                console.log('Payments loaded:', data);
                
                allPayments = data;
                paymentHistory = data;
                pendingPaymentsList = data.filter(p => p.status === "Pending" || p.status === "Overdue");
                
                // Reset pagination when data changes
                pendingCurrentPage = 1;
                historyCurrentPage = 1;
                
                render();
            } catch (error) {
                console.error('Error loading payments:', error);
                const root = document.getElementById('payments-root');
                if (root) {
                    root.innerHTML = `
                        <div class="text-center py-12">
                            <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-gray-900 text-lg mb-2">Failed to load payments</h3>
                            <p class="text-gray-600">Please refresh the page to try again.</p>
                            <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-[#0070FF] text-white rounded-lg">Refresh Page</button>
                        </div>
                    `;
                }
            }
        }

        // Cancel a pending payment
        async function cancelPayment(paymentId) {
            if (confirm('Are you sure you want to cancel this payment? This action cannot be undone.')) {
                try {
                    const response = await fetch(`/member/payments/${paymentId}/cancel`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        alert('Payment cancelled successfully!');
                        await loadPayments();
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Error cancelling payment');
                    }
                } catch (error) {
                    console.error('Error cancelling payment:', error);
                    alert('Error cancelling payment');
                }
            }
        }

        // Pending Payments Pagination
        function pendingPreviousPage() {
            if (pendingCurrentPage > 1) {
                pendingCurrentPage--;
                render();
            }
        }

        function pendingNextPage() {
            const totalPages = Math.ceil(pendingPaymentsList.length / pendingItemsPerPage);
            if (pendingCurrentPage < totalPages) {
                pendingCurrentPage++;
                render();
            }
        }

        // History Pagination
        function historyPreviousPage() {
            if (historyCurrentPage > 1) {
                historyCurrentPage--;
                render();
            }
        }

        function historyNextPage() {
            const filtered = getFilteredHistory();
            const totalPages = Math.ceil(filtered.length / historyItemsPerPage);
            if (historyCurrentPage < totalPages) {
                historyCurrentPage++;
                render();
            }
        }

        function getStatusColor(status) {
            switch (status) {
                case "Paid": return "bg-green-100 text-green-800";
                case "Pending": return "bg-yellow-100 text-yellow-800";
                case "Overdue": return "bg-red-100 text-red-800";
                default: return "bg-gray-100 text-gray-800";
            }
        }

        function formatDisplayDate(dateString) {
            if (!dateString || dateString === "N/A") return "N/A";
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
        }

        async function handlePayNow(payment) {
            selectedPayment = payment;
            gcashNumber = "";
            referenceNumber = "";
            proofFile = null;
            isPaymentDialogOpen = true;
            renderDialog();
        }

        function closePaymentDialog() {
            isPaymentDialogOpen = false;
            selectedPayment = null;
            gcashNumber = "";
            referenceNumber = "";
            proofFile = null;
            const dialog = document.getElementById('paymentDialog');
            if (dialog) dialog.remove();
        }

        async function handleProcessPayment(e) {
            e.preventDefault();

            if (!gcashNumber || !referenceNumber) {
                alert("Please fill in your GCash number and Reference Number");
                return;
            }

            if (!selectedPayment) return;

            isProcessing = true;
            renderDialog();

            try {
                const formData = new FormData();
                formData.append('gcash_number', gcashNumber);
                formData.append('reference_number', referenceNumber);
                if (proofFile) {
                    formData.append('proof_image', proofFile);
                }
                
                const response = await fetch(`/member/payments/${selectedPayment.id}/pay`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (response.ok) {
                    isProcessing = false;
                    isPaymentDialogOpen = false;
                    if (document.getElementById('paymentDialog')) document.getElementById('paymentDialog').remove();

                    gcashNumber = "";
                    referenceNumber = "";
                    proofFile = null;
                    
                    const paidAmount = selectedPayment.amount;
                    selectedPayment = null;

                    await loadPayments();
                    
                    alert(`Payment of ₱${paidAmount.toLocaleString()} has been recorded and is pending admin approval.`);
                    
                } else {
                    const error = await response.json();
                    alert(error.message || 'Payment failed. Please try again.');
                    isProcessing = false;
                }
            } catch (error) {
                console.error('Error processing payment:', error);
                alert('Error processing payment');
                isProcessing = false;
            }
        }

        function filterPayments(status) {
            filterStatus = status;
            historyCurrentPage = 1;
            render();
        }

        function toggleMobileFilters() {
            showFilters = !showFilters;
            render();
        }

        function getFilteredHistory() {
            return allPayments.filter(payment => {
                const matchesFilter = filterStatus === "All" || payment.status === filterStatus;
                const matchesSearch = searchQuery === "" ||
                    (payment.type && payment.type.toLowerCase().includes(searchQuery.toLowerCase())) ||
                    (payment.details && payment.details.toLowerCase().includes(searchQuery.toLowerCase())) ||
                    (payment.admin_message && payment.admin_message.toLowerCase().includes(searchQuery.toLowerCase())) ||
                    (payment.reference_number && payment.reference_number.toLowerCase().includes(searchQuery.toLowerCase()));
                return matchesFilter && matchesSearch;
            });
        }

        function totalPaid() {
            return allPayments.filter(p => p.status === "Paid").reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
        }

        function totalPending() {
            return pendingPaymentsList.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
        }

        function nextPaymentAmount() {
            return pendingPaymentsList[0]?.amount || 0;
        }

        function nextPaymentDate() {
            return pendingPaymentsList[0]?.payment_date || "N/A";
        }

        function filteredHistory() {
            return getFilteredHistory();
        }

        function esc(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function render() {
            const root = document.getElementById('payments-root');
            if (!root) return;

            const filtered = filteredHistory();
            const hasPending = pendingPaymentsList.length > 0;
            
            // Pending Payments Pagination
            const pendingTotalPages = Math.ceil(pendingPaymentsList.length / pendingItemsPerPage);
            const pendingStartIndex = (pendingCurrentPage - 1) * pendingItemsPerPage;
            const pendingEndIndex = pendingStartIndex + pendingItemsPerPage;
            const paginatedPendingPayments = pendingPaymentsList.slice(pendingStartIndex, pendingEndIndex);
            
            // History Pagination
            const historyTotalPages = Math.ceil(filtered.length / historyItemsPerPage);
            const historyStartIndex = (historyCurrentPage - 1) * historyItemsPerPage;
            const historyEndIndex = historyStartIndex + historyItemsPerPage;
            const paginatedHistory = filtered.slice(historyStartIndex, historyEndIndex);

            root.innerHTML = `
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl text-gray-900">My Payments</h1>
                        <p class="text-gray-600 mt-1">View your payment history and track pending payments</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                                <h3 class="text-2xl text-gray-900 font-bold">₱${totalPaid().toLocaleString()}</h3>
                                <p class="text-xs text-green-600 mt-1">Confirmed payments</p>
                            </div>
                        </div>

                        <!-- Pending Approval Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-yellow-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Pending Approval</p>
                            </div>
                            <div class="mt-auto text-right">
                                <h3 class="text-2xl text-gray-900 font-bold">₱${totalPending().toLocaleString()}</h3>
                                <p class="text-xs text-yellow-600 mt-1">${pendingPaymentsList.length} payments waiting</p>
                            </div>
                        </div>

                        <!-- Next Payment Due Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-[#0070FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Next Payment Due</p>
                            </div>
                            <div class="mt-auto text-right">
                                <h3 class="text-2xl text-gray-900 font-bold">₱${nextPaymentAmount().toLocaleString()}</h3>
                                <p class="text-xs text-gray-500 mt-1">${nextPaymentDate() !== "N/A" ? formatDisplayDate(nextPaymentDate()) : "N/A"}</p>
                            </div>
                        </div>

                        <!-- Payment Method Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-purple-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Payment Method</p>
                            </div>
                            <div class="mt-auto text-right">
                                <h3 class="text-xl text-gray-900 font-bold">GCash</h3>
                                <p class="text-xs text-gray-500 mt-1">Mobile Payment</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Payments Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r ${hasPending ? 'from-yellow-50 to-orange-50' : 'from-gray-50 to-gray-100'}">
                            <div class="flex items-center gap-3">
                                <div class="bg-yellow-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg text-gray-900">Payments Pending Approval</h3>
                                    <p class="text-sm text-gray-600 mt-1">You have ${pendingPaymentsList.length} payment${pendingPaymentsList.length !== 1 ? 's' : ''} awaiting admin confirmation</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            ${paginatedPendingPayments.length > 0 ? `
                                <div class="space-y-4">
                                    ${paginatedPendingPayments.map(payment => `
                                        <div class="border border-yellow-200 bg-yellow-50/50 rounded-lg p-6 hover:shadow-md transition-all">
                                            <div class="flex flex-col gap-4">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div class="bg-purple-500 p-2 rounded-lg">
                                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-gray-900 font-medium">${esc(payment.type || 'Payment')}</h4>
                                                            <p class="text-sm text-gray-600 mt-1">${esc(payment.details || 'Payment due')}</p>
                                                        </div>
                                                    </div>
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(payment.status)}">${payment.status}</span>
                                                </div>

                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <p class="text-xs text-gray-600">Amount</p>
                                                        <p class="text-base text-gray-900 font-medium">₱${(parseFloat(payment.amount) || 0).toLocaleString()}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-600">Date</p>
                                                        <p class="text-sm text-gray-900">${formatDisplayDate(payment.payment_date)}</p>
                                                    </div>
                                                    ${payment.gcash_number ? `
                                                        <div>
                                                            <p class="text-xs text-gray-600">GCash Number</p>
                                                            <p class="text-sm text-gray-900">${esc(payment.gcash_number)}</p>
                                                        </div>
                                                    ` : ''}
                                                    ${payment.reference_number ? `
                                                        <div>
                                                            <p class="text-xs text-gray-600">Reference #</p>
                                                            <p class="text-sm text-gray-900">${esc(payment.reference_number)}</p>
                                                        </div>
                                                    ` : ''}
                                                </div>

                                                ${payment.proof_image ? `
                                                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                        <p class="text-xs text-blue-700 font-medium mb-2">📎 Proof of Payment:</p>
                                                        <a href="${payment.proof_image}" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                            </svg>
                                                            View Payment Proof
                                                        </a>
                                                    </div>
                                                ` : ''}

                                                ${payment.admin_message ? `
                                                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                        <p class="text-xs text-blue-700 font-medium mb-1">📝 Message from Admin:</p>
                                                        <p class="text-sm text-blue-600">${esc(payment.admin_message)}</p>
                                                    </div>
                                                ` : ''}

                                                <div class="flex gap-3 mt-2">
                                                    ${!payment.reference_number ? `
                                                        <button onclick='handlePayNow(${JSON.stringify(payment).replace(/'/g, "\\'")})' class="px-6 py-2.5 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors font-medium inline-flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                            </svg>
                                                            Pay via GCash ₱${(parseFloat(payment.amount) || 0).toLocaleString()}
                                                        </button>
                                                        <button onclick='cancelPayment(${payment.id})' class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium inline-flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Cancel Payment
                                                        </button>
                                                    ` : `
                                                        <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg w-full">
                                                            <p class="text-sm text-green-700">✅ Payment recorded! Waiting for admin confirmation.</p>
                                                        </div>
                                                    `}
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                                ${pendingTotalPages > 1 ? `
                                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                        <div class="text-sm text-gray-600">
                                            Showing ${pendingStartIndex + 1} to ${Math.min(pendingEndIndex, pendingPaymentsList.length)} of ${pendingPaymentsList.length} pending payments
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="pendingPreviousPage()" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" ${pendingCurrentPage === 1 ? 'disabled' : ''}>
                                                Previous
                                            </button>
                                            <span class="px-3 py-1 text-sm text-gray-600">Page ${pendingCurrentPage} of ${pendingTotalPages}</span>
                                            <button onclick="pendingNextPage()" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" ${pendingCurrentPage === pendingTotalPages ? 'disabled' : ''}>
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                ` : ''}
                            ` : `
                                <div class="text-center py-8">
                                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">No pending payments at this time.</p>
                                </div>
                            `}
                        </div>
                    </div>

                     <!-- Payment History -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg text-gray-900">Payment History</h3>
                                    <p class="text-sm text-gray-600 mt-1">Showing ${filtered.length} of ${allPayments.length} total payments</p>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    <div class="relative flex-1 lg:w-64">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <input type="text" id="searchInput" placeholder="Search payments..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent text-sm">
                                    </div>

                                    <div class="hidden sm:flex gap-2 bg-gray-100 p-1 rounded-lg">
                                        <button onclick="filterPayments('All')" class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors ${filterStatus === 'All' ? 'bg-[#0070FF] text-white' : 'text-gray-600 hover:text-gray-900'}">All</button>
                                        <button onclick="filterPayments('Paid')" class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors ${filterStatus === 'Paid' ? 'bg-[#0070FF] text-white' : 'text-gray-600 hover:text-gray-900'}">Paid</button>
                                        <button onclick="filterPayments('Pending')" class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors ${filterStatus === 'Pending' ? 'bg-[#0070FF] text-white' : 'text-gray-600 hover:text-gray-900'}">Pending</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${paginatedHistory.length > 0 ? `
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Details</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Ref #</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Proof</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Admin Message</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        ${paginatedHistory.map(payment => `
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <div class="bg-purple-100 p-1.5 rounded">
                                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm text-gray-900">${esc(payment.type || 'Payment')}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-sm text-gray-900 font-medium">${esc(payment.details || 'Payment Transaction')}</p>
                                                    ${payment.gcash_number ? `<p class="text-xs text-gray-500 mt-1">GCash: ${esc(payment.gcash_number)}</p>` : ''}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDisplayDate(payment.payment_date)}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">₱${(parseFloat(payment.amount) || 0).toLocaleString()}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(payment.status)}">${payment.status}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    ${payment.reference_number ? esc(payment.reference_number) : '-'}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    ${payment.proof_image ? `
                                                        <a href="${payment.proof_image}" target="_blank" class="text-[#0070FF] hover:underline text-sm flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                            </svg>
                                                            View
                                                        </a>
                                                    ` : '-'
                                                    }
                                                  </td>
                                                <td class="px-6 py-4">
                                                    ${payment.admin_message ? `
                                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2">
                                                            <p class="text-xs text-blue-700">${esc(payment.admin_message)}</p>
                                                        </div>
                                                    ` : '<span class="text-xs text-gray-400">-</span>'}
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                            ${historyTotalPages > 1 ? `
                                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                    <div class="flex items-center justify-between flex-wrap gap-4">
                                        <div class="text-sm text-gray-600">
                                            Showing ${historyStartIndex + 1} to ${Math.min(historyEndIndex, filtered.length)} of ${filtered.length} payments
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="historyPreviousPage()" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" ${historyCurrentPage === 1 ? 'disabled' : ''}>
                                                Previous
                                            </button>
                                            <span class="px-3 py-1 text-sm text-gray-600">Page ${historyCurrentPage} of ${historyTotalPages}</span>
                                            <button onclick="historyNextPage()" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" ${historyCurrentPage === historyTotalPages ? 'disabled' : ''}>
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                        ` : `
                            <div class="p-12 text-center">
                                <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-gray-900 mb-2">No payment history found</h3>
                                <p class="text-sm text-gray-600">You don't have any payments yet.</p>
                            </div>
                        `}
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div onclick="window.location.href='{{ route('available-trainers') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#E6F0FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-gray-900 font-medium">Book Session</h4>
                                    <p class="text-sm text-gray-600 mt-1">Schedule with trainer</p>
                                </div>
                            </div>
                        </div>

                        <div onclick="window.location.href='{{ route('my-schedule') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#E6F0FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-gray-900 font-medium">View Schedule</h4>
                                    <p class="text-sm text-gray-600 mt-1">See upcoming sessions</p>
                                </div>
                            </div>
                        </div>

                        <div onclick="window.location.href='{{ route('membership') }}'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#E6F0FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-gray-900 font-medium">My Membership</h4>
                                    <p class="text-sm text-gray-600 mt-1">View plan details</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Attach search event listener
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = searchQuery;
                const newSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newSearchInput, searchInput);
                newSearchInput.addEventListener('input', function(e) {
                    searchQuery = e.target.value;
                    historyCurrentPage = 1;
                    render();
                });
            }
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/heic', 'image/heif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPEG, PNG, or HEIC)');
                    event.target.value = '';
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    event.target.value = '';
                    return;
                }
                
                proofFile = file;
                
                const fileNameSpan = document.getElementById('selectedFileName');
                if (fileNameSpan) {
                    fileNameSpan.textContent = file.name;
                }
            }
        }

        function renderDialog() {
            if (!isPaymentDialogOpen || !selectedPayment) return;

            const existingDialog = document.getElementById('paymentDialog');
            if (existingDialog) existingDialog.remove();

            const dialogHtml = `
                <div id="paymentDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full my-8">
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <div>
                                <h3 class="text-lg text-gray-900 font-semibold">GCash Payment</h3>
                                <p class="text-sm text-gray-600">Complete your GCash payment</p>
                            </div>
                            <button onclick="closePaymentDialog()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 max-h-[70vh] overflow-y-auto">
                            <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] rounded-lg p-6 text-white mb-4">
                                <h4 class="text-sm text-white/80 mb-3">Payment Summary</h4>
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-white/90">Type</span>
                                        <span class="font-medium">${selectedPayment.type || 'Payment'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/90">Details</span>
                                        <span class="font-medium">${selectedPayment.details || 'Payment Transaction'}</span>
                                    </div>
                                </div>
                                <div class="h-px bg-white/20 my-3"></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg">Total Amount</span>
                                    <span class="text-2xl font-bold">₱${(parseFloat(selectedPayment.amount) || 0).toLocaleString()}</span>
                                </div>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-semibold text-green-800 mb-2">📱 GCash Payment Instructions</h4>
                                <div class="space-y-2 text-xs text-green-700">
                                    <p>1. Open your GCash app</p>
                                    <p>2. Click "Send Money"</p>
                                    <p>3. Enter GCash Number: <strong>${gcashAccountNumber}</strong></p>
                                    <p>4. Enter Account Name: <strong>${gcashAccountName}</strong></p>
                                    <p>5. Enter amount: <strong>₱${(parseFloat(selectedPayment.amount) || 0).toLocaleString()}</strong></p>
                                    <p>6. Take a screenshot of the transaction</p>
                                    <p>7. Upload the screenshot below</p>
                                </div>
                            </div>

                            <form id="paymentForm" onsubmit="handleProcessPayment(event)">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2 font-medium">Your GCash Number *</label>
                                        <input type="text" id="gcashNumberInput" placeholder="09123456789" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2 font-medium">Reference Number *</label>
                                        <input type="text" id="referenceNumberInput" placeholder="Enter GCash reference number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2 font-medium">Proof of Payment (Screenshot) *</label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#0070FF] transition-colors cursor-pointer" onclick="document.getElementById('proofImageInput').click()">
                                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload screenshot</p>
                                            <p class="text-xs text-gray-500 mt-1">JPEG, PNG, or HEIC (Max 5MB)</p>
                                            <input type="file" id="proofImageInput" accept="image/jpeg,image/png,image/jpg,image/heic,image/heif" class="hidden" onchange="handleFileSelect(event)">
                                            <div id="selectedFileName" class="text-xs text-green-600 mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex gap-3">
                                    <button type="button" onclick="closePaymentDialog()" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                        Cancel
                                    </button>
                                    <button type="submit" id="payButton" class="flex-1 px-4 py-3 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors font-medium" ${isProcessing ? 'disabled' : ''}>
                                        ${isProcessing ? "Processing..." : `Submit Payment ₱${(parseFloat(selectedPayment.amount) || 0).toLocaleString()}`}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', dialogHtml);

            const gcashNumberInput = document.getElementById('gcashNumberInput');
            const referenceNumberInput = document.getElementById('referenceNumberInput');

            if (gcashNumberInput) {
                gcashNumberInput.value = gcashNumber;
                gcashNumberInput.addEventListener('input', function(e) {
                    gcashNumber = e.target.value;
                });
            }
            if (referenceNumberInput) {
                referenceNumberInput.value = referenceNumber;
                referenceNumberInput.addEventListener('input', function(e) {
                    referenceNumber = e.target.value;
                });
            }
        }

        // Make functions global
        window.handlePayNow = handlePayNow;
        window.closePaymentDialog = closePaymentDialog;
        window.handleProcessPayment = handleProcessPayment;
        window.filterPayments = filterPayments;
        window.toggleMobileFilters = toggleMobileFilters;
        window.handleFileSelect = handleFileSelect;
        window.cancelPayment = cancelPayment;
        window.pendingPreviousPage = pendingPreviousPage;
        window.pendingNextPage = pendingNextPage;
        window.historyPreviousPage = historyPreviousPage;
        window.historyNextPage = historyNextPage;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Payment page loaded - GCash payments with proof of payment upload');
            checkAndCreatePendingPayment();
        });
    </script>
    @endpush
</x-app-layout>