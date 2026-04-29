@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">Membership Plans</h1>
                <p class="text-gray-600 mt-1">Manage your gym membership plans</p>
            </div>
            <button onclick="openAddPlanModal()" class="flex items-center gap-2 px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm">Add New Plan</span>
            </button>
        </div>

        <!-- Membership Cards -->
        <div id="plansContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Plans will be loaded here -->
        </div>
    </div>
</div>

<!-- Add Plan Modal -->
<div id="addPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white flex items-center justify-between">
            <h3 class="text-lg text-gray-900 font-semibold">Add New Membership Plan</h3>
            <button onclick="closeAddPlanModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Plan Name *</label>
                    <input type="text" id="addPlanName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="e.g., Premium">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Price (₱/month) *</label>
                    <input type="number" id="addPlanPrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="999">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Duration Label *</label>
                    <input type="text" id="addPlanDuration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="e.g., 3 Months">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Duration (Days) *</label>
                    <input type="number" id="addPlanDurationDays" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="90">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1 font-medium">Benefits / Features *</label>
                <div id="addFeaturesContainer" class="space-y-2"></div>
                <button onclick="addFeature('add')" class="mt-2 flex items-center gap-2 px-3 py-2 text-sm text-[#0070FF] hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Feature
                </button>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="addPlanPopular" class="w-4 h-4 text-[#0070FF] rounded focus:ring-2 focus:ring-[#0070FF]">
                    <span class="text-sm text-gray-700">Mark as Popular</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="addPlanActive" class="w-4 h-4 text-[#0070FF] rounded focus:ring-2 focus:ring-[#0070FF]" checked>
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-3 sticky bottom-0 bg-white">
            <button onclick="closeAddPlanModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button onclick="saveNewPlan()" class="px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-blue-600 transition-colors">
                Add Plan
            </button>
        </div>
    </div>
</div>

<!-- Edit Plan Modal -->
<div id="editPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white flex items-center justify-between">
            <h3 class="text-lg text-gray-900 font-semibold">Edit Membership Plan</h3>
            <button onclick="closeEditPlanModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="editPlanId">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Plan Name *</label>
                    <input type="text" id="editPlanName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="e.g., Premium">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Price (₱/month) *</label>
                    <input type="number" id="editPlanPrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="999">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Duration Label *</label>
                    <input type="text" id="editPlanDuration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="e.g., 3 Months">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1 font-medium">Duration (Days) *</label>
                    <input type="number" id="editPlanDurationDays" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="90">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1 font-medium">Benefits / Features *</label>
                <div id="editFeaturesContainer" class="space-y-2"></div>
                <button onclick="addFeature('edit')" class="mt-2 flex items-center gap-2 px-3 py-2 text-sm text-[#0070FF] hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Feature
                </button>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="editPlanPopular" class="w-4 h-4 text-[#0070FF] rounded focus:ring-2 focus:ring-[#0070FF]">
                    <span class="text-sm text-gray-700">Mark as Popular</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="editPlanActive" class="w-4 h-4 text-[#0070FF] rounded focus:ring-2 focus:ring-[#0070FF]">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-3 sticky bottom-0 bg-white">
            <button onclick="closeEditPlanModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button onclick="updatePlan()" class="px-4 py-2 bg-[#0070FF] text-white rounded-lg hover:bg-blue-600 transition-colors">
                Save Changes
            </button>
        </div>
    </div>
</div>

<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let currentEditId = null;
    let addFeaturesList = [""];
    let editFeaturesList = [""];

    // Load plans from database
    async function loadPlans() {
        try {
            const response = await fetch('/admin/membership-plans/data');
            const data = await response.json();
            window.plans = data;
            renderPlans();
        } catch (error) {
            console.error('Error loading plans:', error);
        }
    }

    function renderPlans() {
        const container = document.getElementById('plansContainer');
        
        if (!window.plans || window.plans.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No membership plans found. Click "Add New Plan" to create one.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = window.plans.map(plan => `
            <div class="bg-white rounded-2xl border ${plan.popular ? 'border-[#0070FF] border-2' : 'border-gray-200'} shadow-sm hover:shadow-md transition-all duration-200 relative ${!plan.active ? 'opacity-60' : ''}">
                ${plan.popular ? `
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-[#0070FF] text-white px-4 py-1 rounded-full text-xs font-medium">Most Popular</span>
                    </div>
                ` : ''}
                ${!plan.active ? `
                    <div class="absolute top-4 right-4">
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>
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
                                <svg class="w-5 h-5 text-[#0070FF] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">${escapeHtml(feature)}</span>
                            </li>
                        `).join('')}
                    </ul>
                    <div class="border-t border-gray-200 mb-4"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <button onclick="openEditPlanModal(${plan.id})" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <span class="text-sm">Edit</span>
                            </button>
                            <button onclick="deletePlan(${plan.id})" class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="text-sm">Delete</span>
                            </button>
                        </div>
                        <button onclick="togglePlanStatus(${plan.id})" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg transition-colors ${plan.active ? 'bg-blue-50 text-blue-700 hover:bg-blue-100' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                            ${plan.active ? `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            ` : `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 9l4 4-4 4"></path>
                                </svg>
                            `}
                            <span class="text-sm">${plan.active ? 'Active' : 'Inactive'}</span>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Feature Management
    function renderAddFeatures() {
        const container = document.getElementById('addFeaturesContainer');
        container.innerHTML = addFeaturesList.map((feature, index) => `
            <div class="flex items-center gap-2">
                <input type="text" value="${escapeHtml(feature)}" onchange="updateAddFeature(${index}, this.value)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="Enter a feature">
                ${addFeaturesList.length > 1 ? `
                    <button onclick="removeAddFeature(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                ` : ''}
            </div>
        `).join('');
    }

    function renderEditFeatures() {
        const container = document.getElementById('editFeaturesContainer');
        container.innerHTML = editFeaturesList.map((feature, index) => `
            <div class="flex items-center gap-2">
                <input type="text" value="${escapeHtml(feature)}" onchange="updateEditFeature(${index}, this.value)" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF]" placeholder="Enter a feature">
                ${editFeaturesList.length > 1 ? `
                    <button onclick="removeEditFeature(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                ` : ''}
            </div>
        `).join('');
    }

    function addFeature(type) {
        if (type === 'add') {
            addFeaturesList.push("");
            renderAddFeatures();
        } else {
            editFeaturesList.push("");
            renderEditFeatures();
        }
    }

    function updateAddFeature(index, value) {
        addFeaturesList[index] = value;
    }

    function updateEditFeature(index, value) {
        editFeaturesList[index] = value;
    }

    function removeAddFeature(index) {
        addFeaturesList = addFeaturesList.filter((_, i) => i !== index);
        renderAddFeatures();
    }

    function removeEditFeature(index) {
        editFeaturesList = editFeaturesList.filter((_, i) => i !== index);
        renderEditFeatures();
    }

    // Modal Functions
    function openAddPlanModal() {
        addFeaturesList = [""];
        renderAddFeatures();
        document.getElementById('addPlanName').value = '';
        document.getElementById('addPlanPrice').value = '';
        document.getElementById('addPlanDuration').value = '';
        document.getElementById('addPlanDurationDays').value = '';
        document.getElementById('addPlanPopular').checked = false;
        document.getElementById('addPlanActive').checked = true;
        document.getElementById('addPlanModal').classList.remove('hidden');
        document.getElementById('addPlanModal').classList.add('flex');
    }

    function closeAddPlanModal() {
        document.getElementById('addPlanModal').classList.add('hidden');
        document.getElementById('addPlanModal').classList.remove('flex');
    }

    function openEditPlanModal(id) {
        const plan = window.plans.find(p => p.id === id);
        if (plan) {
            currentEditId = id;
            editFeaturesList = [...plan.features];
            renderEditFeatures();
            document.getElementById('editPlanId').value = plan.id;
            document.getElementById('editPlanName').value = plan.name;
            document.getElementById('editPlanPrice').value = plan.price;
            document.getElementById('editPlanDuration').value = plan.duration;
            document.getElementById('editPlanDurationDays').value = plan.duration_days;
            document.getElementById('editPlanPopular').checked = plan.popular || false;
            document.getElementById('editPlanActive').checked = plan.active;
            document.getElementById('editPlanModal').classList.remove('hidden');
            document.getElementById('editPlanModal').classList.add('flex');
        }
    }

    function closeEditPlanModal() {
        document.getElementById('editPlanModal').classList.add('hidden');
        document.getElementById('editPlanModal').classList.remove('flex');
        currentEditId = null;
    }

    async function saveNewPlan() {
        const formData = {
            name: document.getElementById('addPlanName').value,
            duration: document.getElementById('addPlanDuration').value,
            duration_days: parseInt(document.getElementById('addPlanDurationDays').value) || 0,
            price: parseFloat(document.getElementById('addPlanPrice').value) || 0,
            features: addFeaturesList.filter(f => f.trim() !== ""),
            popular: document.getElementById('addPlanPopular').checked,
            active: document.getElementById('addPlanActive').checked,
        };

        if (!formData.name || !formData.duration || !formData.duration_days || !formData.price || formData.features.length === 0) {
            alert("Please fill in all required fields");
            return;
        }

        try {
            const response = await fetch('/admin/membership-plans', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });
            
            if (response.ok) {
                closeAddPlanModal();
                await loadPlans();
                alert("Plan added successfully!");
            } else {
                const error = await response.json();
                alert(error.message || "Error saving plan");
            }
        } catch (error) {
            console.error('Error saving plan:', error);
            alert("Error saving plan");
        }
    }

    async function updatePlan() {
        const formData = {
            name: document.getElementById('editPlanName').value,
            duration: document.getElementById('editPlanDuration').value,
            duration_days: parseInt(document.getElementById('editPlanDurationDays').value) || 0,
            price: parseFloat(document.getElementById('editPlanPrice').value) || 0,
            features: editFeaturesList.filter(f => f.trim() !== ""),
            popular: document.getElementById('editPlanPopular').checked,
            active: document.getElementById('editPlanActive').checked,
        };

        if (!formData.name || !formData.duration || !formData.duration_days || !formData.price || formData.features.length === 0) {
            alert("Please fill in all required fields");
            return;
        }

        try {
            const response = await fetch(`/admin/membership-plans/${currentEditId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });
            
            if (response.ok) {
                closeEditPlanModal();
                await loadPlans();
                alert("Plan updated successfully!");
            } else {
                const error = await response.json();
                alert(error.message || "Error updating plan");
            }
        } catch (error) {
            console.error('Error updating plan:', error);
            alert("Error updating plan");
        }
    }

    async function deletePlan(id) {
        if (confirm("Are you sure you want to delete this plan?")) {
            try {
                const response = await fetch(`/admin/membership-plans/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                if (response.ok) {
                    await loadPlans();
                    alert("Plan deleted successfully!");
                } else {
                    alert("Error deleting plan");
                }
            } catch (error) {
                console.error('Error deleting plan:', error);
                alert("Error deleting plan");
            }
        }
    }

    async function togglePlanStatus(id) {
        const plan = window.plans.find(p => p.id === id);
        if (plan) {
            try {
                const response = await fetch(`/admin/membership-plans/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ...plan, active: !plan.active }),
                });
                
                if (response.ok) {
                    await loadPlans();
                } else {
                    alert("Error updating plan status");
                }
            } catch (error) {
                console.error('Error toggling plan status:', error);
                alert("Error updating plan status");
            }
        }
    }

    // Initial load
    loadPlans();
</script>
@endsection