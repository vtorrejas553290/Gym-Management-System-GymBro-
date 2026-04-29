<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Membership') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Refresh Button -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl text-gray-900">My Membership</h1>
                    <p class="text-gray-600 mt-1">Manage your gym membership plan</p>
                </div>
                <button onclick="loadCurrentMembership()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center gap-2 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>

            <!-- Current Membership Card -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-[#0070FF] overflow-hidden mb-12">
                <div class="bg-gradient-to-r from-[#0070FF] to-[#005FCC] px-6 py-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-white/80">Current Plan</p>
                                <h2 id="currentPlanName" class="text-xl font-semibold">Loading...</h2>
                            </div>
                        </div>
                        <span id="membershipStatus" class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                            Loading...
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Price</p>
                                <p id="currentPrice" class="text-sm text-gray-900 font-semibold">Loading...</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Duration</p>
                                <p id="currentDuration" class="text-sm text-gray-900 font-semibold">Loading...</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="bg-[#E6F0FF] p-2 rounded-lg">
                                <svg class="w-4 h-4 text-[#0070FF]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Remaining Days</p>
                                <p id="remainingDays" class="text-sm text-gray-900 font-semibold">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-4">
                        <div>
                            <p class="text-xs text-gray-600">Start Date</p>
                            <p id="startDate" class="text-sm text-gray-900 font-medium">Loading...</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-600">Expiry Date</p>
                            <p id="expiryDate" class="text-sm text-gray-900 font-medium">Loading...</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="openRenewModal()" class="flex-1 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors text-sm font-medium">
                            Renew Plan
                        </button>
                        <button onclick="scrollToPlans()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                            Upgrade Plan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Available Plans Section -->
            <div id="plansSection">
                <div class="mb-6">
                    <h2 class="text-2xl text-gray-900">Available Membership Plans</h2>
                    <p class="text-gray-600 mt-1">Choose the perfect plan for your fitness journey</p>
                </div>

                <div id="plansContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Plans will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Select Plan Modal -->
    <div id="selectPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg text-gray-900 font-semibold">Confirm Plan Selection</h3>
                <button onclick="closeSelectModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="selectedPlanDetails" class="bg-[#E6F0FF] border border-[#0070FF] rounded-lg p-4 mb-6"></div>
                <div class="space-y-3 mb-6">
                    <h5 class="text-sm text-gray-700 font-medium">Included Benefits:</h5>
                    <div id="selectedPlanFeatures" class="space-y-2"></div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700">
                        <strong>Note:</strong> You will be redirected to the payment page to complete your membership purchase.
                    </p>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button onclick="closeSelectModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmSelection()" class="flex-1 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors font-medium">
                    Proceed to Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Renew Plan Modal -->
    <div id="renewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg text-gray-900 font-semibold">Renew Membership</h3>
                <button onclick="closeRenewModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="renewPlanDetails" class="bg-[#E6F0FF] border border-[#0070FF] rounded-lg p-4 mb-6"></div>
                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Current expiry:</span>
                        <span id="currentExpiry" class="text-gray-900 font-medium">Loading...</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">New expiry:</span>
                        <span id="newExpiry" class="text-gray-900 font-medium">Loading...</span>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p id="renewMessage" class="text-sm text-gray-700">
                        Your membership will be renewed for another period.
                    </p>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button onclick="closeRenewModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmRenewal()" class="flex-1 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-[#005FCC] transition-colors font-medium">
                    Proceed to Payment
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        let selectedPlanData = null;
        let currentMemberPlan = null;
        let refreshInterval = null;

        // Load member's current membership from database
        async function loadCurrentMembership() {
            try {
                const response = await fetch('/member/membership/current');
                const data = await response.json();
                if (data) {
                    currentMemberPlan = data;
                    
                    document.getElementById('currentPlanName').textContent = `${data.plan_name} Membership`;
                    document.getElementById('membershipStatus').textContent = data.status;
                    document.getElementById('currentPrice').textContent = data.price === 0 ? 'N/A' : `₱${data.price}/${data.duration}`;
                    document.getElementById('currentDuration').textContent = data.duration === 'N/A' ? 'No active plan' : data.duration;
                    document.getElementById('remainingDays').textContent = data.remaining_days === 0 ? 'N/A' : `${data.remaining_days} days`;
                    document.getElementById('startDate').textContent = data.start_date === 'N/A' ? 'N/A' : data.start_date;
                    document.getElementById('expiryDate').textContent = data.expiry_date === 'N/A' ? 'N/A' : data.expiry_date;
                    
                    console.log('Membership data refreshed:', data);
                }
            } catch (error) {
                console.error('Error loading current membership:', error);
            }
        }

        // Load available plans from database
        async function loadPlans() {
            try {
                const response = await fetch('/member/membership-plans');
                const data = await response.json();
                renderPlans(data);
            } catch (error) {
                console.error('Error loading plans:', error);
            }
        }

        function renderPlans(plans) {
            const container = document.getElementById('plansContainer');
            
            if (!plans || plans.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No membership plans available at the moment.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = plans.map(plan => `
                <div class="bg-white rounded-2xl border ${plan.popular ? 'border-2 border-[#0070FF]' : 'border-gray-200'} shadow-sm hover:shadow-md transition-all duration-200 relative flex flex-col h-full">
                    ${plan.popular ? `
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 z-10">
                            <span class="bg-[#0070FF] text-white px-4 py-1 rounded-full text-xs flex items-center gap-1 font-medium whitespace-nowrap">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2z"></path>
                                </svg>
                                Recommended
                            </span>
                        </div>
                    ` : ''}
                    <div class="p-6 flex flex-col h-full">
                        <div class="text-center mb-6">
                            <h3 class="text-xl text-gray-900 font-semibold">${escapeHtml(plan.name)}</h3>
                            <p class="text-sm text-gray-600 mt-1">${escapeHtml(plan.duration)}</p>
                        </div>
                        <div class="text-center mb-6">
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl text-gray-900 font-bold">₱${plan.price.toLocaleString()}</span>
                                <span class="text-gray-600 ml-1">/mo</span>
                            </div>
                        </div>
                        <ul class="space-y-3 flex-grow mb-6">
                            ${plan.features.map(feature => `
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-[#0070FF] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">${escapeHtml(feature)}</span>
                                </li>
                            `).join('')}
                        </ul>
                        <div class="border-t border-gray-200 pt-4 mt-auto">
                            <button onclick="openSelectModal(${plan.id}, '${escapeHtml(plan.name)}', ${plan.price}, '${escapeHtml(plan.duration)}', ${JSON.stringify(plan.features).replace(/"/g, '&quot;')})" class="w-full py-3 rounded-xl text-sm transition-all duration-200 ${plan.popular ? 'bg-[#0070FF] text-white hover:bg-[#005FCC] shadow-sm' : 'bg-white border border-gray-200 text-gray-900 hover:border-[#0070FF] hover:text-[#0070FF]'} font-medium">
                                ${plan.popular ? 'Select Plan' : 'Choose Plan'}
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function openSelectModal(id, name, price, duration, features) {
            selectedPlanData = { id, name, price, duration, features };
            
            document.getElementById('selectedPlanDetails').innerHTML = `
                <h4 class="text-lg text-gray-900 font-semibold mb-2">${name} Plan</h4>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-3xl text-gray-900 font-bold">₱${price.toLocaleString()}</span>
                    <span class="text-gray-600">/month</span>
                </div>
                <p class="text-sm text-gray-600">Duration: ${duration}</p>
            `;
            
            const featuresHtml = features.map(feature => `
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-[#0070FF] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">${feature}</span>
                </div>
            `).join('');
            
            document.getElementById('selectedPlanFeatures').innerHTML = featuresHtml;
            document.getElementById('selectPlanModal').classList.remove('hidden');
            document.getElementById('selectPlanModal').classList.add('flex');
        }

        function closeSelectModal() {
            document.getElementById('selectPlanModal').classList.add('hidden');
            document.getElementById('selectPlanModal').classList.remove('flex');
            selectedPlanData = null;
        }

        async function confirmSelection() {
            if (selectedPlanData) {
                try {
                    const response = await fetch('/member/membership/select', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ plan_id: selectedPlanData.id }),
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        alert(`${selectedPlanData.name} plan selected! Redirecting to payment...`);
                        closeSelectModal();
                        window.location.href = '{{ route("payment") }}';
                    } else {
                        alert(data.message || 'Error selecting plan. Please try again.');
                    }
                } catch (error) {
                    console.error('Error selecting plan:', error);
                    alert('Error selecting plan. Please try again.');
                }
            }
        }

        function openRenewModal() {
            if (currentMemberPlan) {
                document.getElementById('renewPlanDetails').innerHTML = `
                    <h4 class="text-lg text-gray-900 font-semibold mb-2">${currentMemberPlan.plan_name} Plan</h4>
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-3xl text-gray-900 font-bold">₱${currentMemberPlan.price.toLocaleString()}</span>
                        <span class="text-gray-600">/${currentMemberPlan.duration}</span>
                    </div>
                `;
                
                const currentExpiry = new Date(currentMemberPlan.expiry_date);
                const newExpiry = new Date(currentExpiry);
                const durationDays = currentMemberPlan.duration_days || 90;
                newExpiry.setDate(newExpiry.getDate() + durationDays);
                
                document.getElementById('currentExpiry').textContent = currentMemberPlan.expiry_date;
                document.getElementById('newExpiry').textContent = newExpiry.toISOString().split('T')[0];
                document.getElementById('renewMessage').innerHTML = `Your ${currentMemberPlan.plan_name} membership will be renewed for another ${currentMemberPlan.duration}.`;
                
                document.getElementById('renewModal').classList.remove('hidden');
                document.getElementById('renewModal').classList.add('flex');
            }
        }

        function closeRenewModal() {
            document.getElementById('renewModal').classList.add('hidden');
            document.getElementById('renewModal').classList.remove('flex');
        }

        async function confirmRenewal() {
            try {
                const response = await fetch('/member/membership/renew', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    alert("Membership renewal initiated! Redirecting to payment...");
                    closeRenewModal();
                    window.location.href = '{{ route("payment") }}';
                } else {
                    alert(data.message || 'Error renewing membership. Please try again.');
                }
            } catch (error) {
                console.error('Error renewing membership:', error);
                alert('Error renewing membership. Please try again.');
            }
        }

        function scrollToPlans() {
            document.getElementById('plansSection').scrollIntoView({ behavior: 'smooth' });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCurrentMembership();
            loadPlans();
            
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            refreshInterval = setInterval(() => {
                loadCurrentMembership();
            }, 60000);
        });
    </script>
    @endpush
</x-app-layout>