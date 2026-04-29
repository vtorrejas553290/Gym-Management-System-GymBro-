<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl text-gray-900">My Profile</h1>
                    <p class="text-gray-600 mt-1">Manage your personal information and membership</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-[#0070FF] to-[#00C9FF] rounded-full flex items-center justify-center mb-4">
                                    <span class="text-white text-3xl font-medium" id="avatarInitials">JD</span>
                                </div>
                                <h3 class="text-xl text-gray-900 font-semibold" id="displayName">Loading...</h3>
                                <p class="text-gray-600 mt-1">Member ID: <span id="memberId">-</span></p>
                                <span id="membershipStatusBadge" class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Active</span>
                            </div>

                            <!-- View Mode -->
                            <div id="viewMode" class="mt-6 space-y-4 border-t border-gray-200 pt-6">
                                <div class="flex items-center gap-3 text-gray-700">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    <span class="text-sm" id="viewEmail">Loading...</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-700">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-sm" id="viewPhone">Loading...</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-700">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm">Joined <span id="joinDate">Loading...</span></span>
                                </div>
                                @if(!session('isEditing'))
                                <button onclick="enableEditing()" id="editProfileBtn" class="w-full mt-4 bg-[#0070FF] hover:bg-[#0060DD] text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit Profile
                                </button>
                                @endif
                            </div>

                            <!-- Edit Mode -->
                            <form id="editMode" class="mt-6 space-y-4 border-t border-gray-200 pt-6 hidden">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="editName" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="editEmail" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" id="editPhone" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                                </div>
                                <div class="flex gap-2 pt-4">
                                    <button type="button" onclick="cancelEditing()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 flex items-center justify-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancel
                                    </button>
                                    <button type="button" onclick="saveProfile()" class="flex-1 bg-[#0070FF] hover:bg-[#0060DD] text-white rounded-lg py-2 flex items-center justify-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Membership & Activity Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Membership Info -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg text-gray-900 font-semibold mb-4">Membership Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Current Plan</p>
                                    </div>
                                    <p id="membershipPlan" class="text-xl text-gray-900 font-semibold">Loading...</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Next Payment</p>
                                    </div>
                                    <p id="nextPayment" class="text-xl text-gray-900 font-semibold">Loading...</p>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <button onclick="window.location.href='/membership'" class="w-full bg-[#0070FF] hover:bg-[#0060DD] text-white px-4 py-2 rounded-lg transition-colors">
                                    Upgrade Membership
                                </button>
                            </div>
                        </div>

                        <!-- Activity Stats -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg text-gray-900 font-semibold mb-4">Activity Summary</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#0070FF] p-3 rounded-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Sessions Completed</p>
                                            <p id="sessionsCompleted" class="text-2xl text-gray-900 font-bold">0</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-green-500 p-3 rounded-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Upcoming Sessions</p>
                                            <p id="upcomingSessions" class="text-2xl text-gray-900 font-bold">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Payments -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg text-gray-900 font-semibold">Recent Payments</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentsTableBody" class="divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Loading payments...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let memberData = {};

        // Load member data from API
        async function loadMemberData() {
            try {
                const response = await fetch('/member/profile/data');
                if (response.ok) {
                    memberData = await response.json();
                    updateUI();
                } else {
                    console.error('Failed to load member data');
                }
            } catch (error) {
                console.error('Error loading member data:', error);
            }
        }

        async function loadRecentPayments() {
            try {
                const response = await fetch('/member/payments/recent');
                if (response.ok) {
                    const payments = await response.json();
                    renderPaymentsTable(payments);
                }
            } catch (error) {
                console.error('Error loading payments:', error);
            }
        }

        function renderPaymentsTable(payments) {
            const tbody = document.getElementById('paymentsTableBody');
            
            if (!payments || payments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No payment records found<\/td><\/tr>';
                return;
            }
            
            tbody.innerHTML = payments.map(payment => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(payment.payment_date)}<\/td>
                    <td class="px-6 py-4 text-sm text-gray-900">${escapeHtml(payment.type)}<\/td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">₱${parseFloat(payment.amount).toLocaleString()}<\/td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium ${getPaymentStatusColor(payment.status)}">
                            ${payment.status}
                        <\/span>
                    <\/td>
                <\/tr>
            `).join('');
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        function getPaymentStatusColor(status) {
            switch(status) {
                case 'Paid': return 'bg-green-100 text-green-800';
                case 'Pending': return 'bg-yellow-100 text-yellow-800';
                case 'Overdue': return 'bg-red-100 text-red-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updateUI() {
            const fullName = `${memberData.first_name || ''} ${memberData.middle_name ? memberData.middle_name + ' ' : ''}${memberData.last_name || ''}`;
            document.getElementById('displayName').innerText = fullName || 'Member User';
            document.getElementById('memberId').innerText = memberData.id || '-';
            document.getElementById('viewEmail').innerText = memberData.email || '-';
            document.getElementById('viewPhone').innerText = memberData.phone || '-';
            document.getElementById('joinDate').innerText = memberData.join_date || '-';
            
            const initials = (memberData.first_name?.[0] || '') + (memberData.last_name?.[0] || '');
            document.getElementById('avatarInitials').innerText = initials || 'M';
            
            document.getElementById('membershipPlan').innerText = memberData.plan || 'Basic';
            document.getElementById('nextPayment').innerText = memberData.next_payment || 'N/A';
            
            const status = memberData.status || 'Active';
            const statusBadge = document.getElementById('membershipStatusBadge');
            statusBadge.innerText = status;
            statusBadge.className = `mt-4 px-4 py-2 rounded-full text-sm font-medium ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
            
            document.getElementById('editName').value = fullName;
            document.getElementById('editEmail').value = memberData.email || '';
            document.getElementById('editPhone').value = memberData.phone || '';
            
            document.getElementById('sessionsCompleted').innerText = memberData.sessions_completed || 0;
            document.getElementById('upcomingSessions').innerText = memberData.upcoming_sessions || 0;
            
            loadRecentPayments();
        }

        function enableEditing() {
            document.getElementById('viewMode').classList.add('hidden');
            document.getElementById('editMode').classList.remove('hidden');
        }

        function cancelEditing() {
            document.getElementById('viewMode').classList.remove('hidden');
            document.getElementById('editMode').classList.add('hidden');
            
            const fullName = `${memberData.first_name || ''} ${memberData.middle_name ? memberData.middle_name + ' ' : ''}${memberData.last_name || ''}`;
            document.getElementById('editName').value = fullName;
            document.getElementById('editEmail').value = memberData.email || '';
            document.getElementById('editPhone').value = memberData.phone || '';
        }

        async function saveProfile() {
            const formData = {
                name: document.getElementById('editName').value,
                email: document.getElementById('editEmail').value,
                phone: document.getElementById('editPhone').value,
            };
            
            try {
                const response = await fetch('/member/profile/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    alert('Profile updated successfully!');
                    await loadMemberData();
                    cancelEditing();
                } else {
                    const error = await response.json();
                    alert(error.message || 'Error updating profile');
                }
            } catch (error) {
                console.error('Error saving profile:', error);
                alert('Error saving profile');
            }
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadMemberData();
        });
    </script>
    @endpush
</x-app-layout>