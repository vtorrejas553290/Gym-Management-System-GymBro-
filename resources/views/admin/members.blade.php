@extends('admin.layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl text-gray-900">Members</h1>
                <p class="text-gray-600 mt-1">Manage your gym members</p>
            </div>
            <button onclick="openMemberModal()" class="bg-[#0070FF] hover:bg-[#0060DD] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Member
            </button>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by name, email, or ID..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Membership Plan</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody" class="divide-y divide-gray-200">
                        <!-- Members will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Member Modal -->
<div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-xl text-gray-900">Add New Member</h2>
            <button onclick="closeMemberModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="memberForm" class="p-6 space-y-4">
            <input type="hidden" id="editMemberId">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="memberFirstName" placeholder="Enter first name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="memberLastName" placeholder="Enter last name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                <input type="text" id="memberMiddleName" placeholder="Enter middle name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="memberEmail" placeholder="Enter email" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="memberPhone" placeholder="Enter phone number" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="memberPassword" placeholder="Enter password" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                    <p class="text-xs text-gray-500">Leave blank to keep current password</p>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="memberPasswordConfirmation" placeholder="Confirm password" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Membership Plan</label>
                <select id="memberPlan" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                    <option value="Basic">Basic</option>
                    <option value="Premium">Premium</option>
                    <option value="VIP">VIP</option>
                    <option value="Annual">Annual</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="memberStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeMemberModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-[#0070FF] hover:bg-[#0060DD] text-white rounded-lg py-2 transition-colors">
                    Add Member
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let currentEditingId = null;
    let searchTerm = "";

    // Load members from database
    async function loadMembers() {
        try {
            const response = await fetch('/admin/members/data');
            const data = await response.json();
            window.members = data;
            renderMembersTable();
        } catch (error) {
            console.error('Error loading members:', error);
        }
    }

    function renderMembersTable() {
        const filtered = window.members.filter(member => {
            const fullName = `${member.first_name} ${member.middle_name ? member.middle_name + ' ' : ''}${member.last_name}`;
            return fullName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                member.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                member.id.toString().includes(searchTerm.toLowerCase());
        });

        const tbody = document.getElementById('membersTableBody');
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No members found
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = filtered.map(member => {
            const fullName = `${member.first_name} ${member.middle_name ? member.middle_name + ' ' : ''}${member.last_name}`;
            // Display plan, default to 'Basic' if empty
            const displayPlan = member.plan && member.plan !== '' ? member.plan : 'Basic';
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
                            <p class="text-xs text-gray-500">ID: ${member.id}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(member.email)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(member.phone)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-medium ${getPlanColor(displayPlan)}">
                        ${escapeHtml(displayPlan)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium ${member.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${member.status || 'Active'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2">
                        <button onclick="editMember(${member.id})" class="p-2 hover:bg-blue-50 rounded-lg text-blue-600 transition-colors" title="Edit Member">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteMember(${member.id})" class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors" title="Delete Member">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                 </td>
              </tr>
        `}).join('');
    }

    // Helper function to get plan color
    function getPlanColor(plan) {
        switch(plan) {
            case 'Basic': return 'bg-blue-100 text-blue-800';
            case 'Premium': return 'bg-purple-100 text-purple-800';
            case 'VIP': return 'bg-yellow-100 text-yellow-800';
            case 'Annual': return 'bg-green-100 text-green-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function openMemberModal() {
        currentEditingId = null;
        document.getElementById('modalTitle').innerText = 'Add New Member';
        document.getElementById('memberForm').reset();
        document.getElementById('editMemberId').value = '';
        document.getElementById('memberFirstName').value = '';
        document.getElementById('memberMiddleName').value = '';
        document.getElementById('memberLastName').value = '';
        document.getElementById('memberEmail').value = '';
        document.getElementById('memberPhone').value = '';
        document.getElementById('memberPassword').value = '';
        document.getElementById('memberPasswordConfirmation').value = '';
        document.getElementById('memberPlan').value = 'Basic';
        document.getElementById('memberStatus').value = 'Active';
        document.getElementById('memberModal').classList.remove('hidden');
        document.getElementById('memberModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeMemberModal() {
        document.getElementById('memberModal').classList.add('hidden');
        document.getElementById('memberModal').classList.remove('flex');
        document.body.style.overflow = '';
        currentEditingId = null;
    }

    function editMember(id) {
        const member = window.members.find(m => m.id === id);
        if (member) {
            currentEditingId = id;
            document.getElementById('modalTitle').innerText = 'Edit Member';
            document.getElementById('editMemberId').value = member.id;
            document.getElementById('memberFirstName').value = member.first_name;
            document.getElementById('memberMiddleName').value = member.middle_name || '';
            document.getElementById('memberLastName').value = member.last_name;
            document.getElementById('memberEmail').value = member.email;
            document.getElementById('memberPhone').value = member.phone;
            document.getElementById('memberPlan').value = member.plan || 'Basic';
            document.getElementById('memberStatus').value = member.status || 'Active';
            document.getElementById('memberPassword').value = '';
            document.getElementById('memberPasswordConfirmation').value = '';
            document.getElementById('memberModal').classList.remove('hidden');
            document.getElementById('memberModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    async function deleteMember(id) {
        if (confirm('Are you sure you want to delete this member?')) {
            try {
                const response = await fetch(`/admin/members/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                if (response.ok) {
                    await loadMembers();
                    alert('Member deleted successfully');
                } else {
                    alert('Error deleting member');
                }
            } catch (error) {
                console.error('Error deleting member:', error);
                alert('Error deleting member');
            }
        }
    }

    // Handle form submission
    document.getElementById('memberForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const password = document.getElementById('memberPassword').value;
        const passwordConfirmation = document.getElementById('memberPasswordConfirmation').value;
        
        if (password !== passwordConfirmation) {
            alert('Password and Confirm Password do not match');
            return;
        }
        
        const formData = {
            first_name: document.getElementById('memberFirstName').value,
            middle_name: document.getElementById('memberMiddleName').value || null,
            last_name: document.getElementById('memberLastName').value,
            email: document.getElementById('memberEmail').value,
            phone: document.getElementById('memberPhone').value,
            plan: document.getElementById('memberPlan').value,
            status: document.getElementById('memberStatus').value,
        };
        
        if (password) {
            formData.password = password;
        }

        try {
            let response;
            if (currentEditingId) {
                // Update existing member
                response = await fetch(`/admin/members/${currentEditingId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
            } else {
                // Add new member
                if (!password) {
                    alert('Password is required for new members');
                    return;
                }
                response = await fetch('/admin/members', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
            }
            
            if (response.ok) {
                closeMemberModal();
                await loadMembers();
                alert(currentEditingId ? 'Member updated successfully!' : 'Member added successfully!');
            } else {
                const error = await response.json();
                alert(error.message || 'Error saving member');
            }
        } catch (error) {
            console.error('Error saving member:', error);
            alert('Error saving member');
        }
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value;
        renderMembersTable();
    });

    // Initial load
    loadMembers();
</script>
@endsection