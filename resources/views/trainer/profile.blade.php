@extends('layouts.trainer-app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl text-gray-900">Trainer Profile</h1>
                <p class="text-gray-600 mt-1">Manage your professional information</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-[#0070FF] to-[#00C9FF] rounded-full flex items-center justify-center mb-4">
                                <span class="text-white text-3xl font-medium" id="avatarInitials">TC</span>
                            </div>
                            <h3 class="text-xl text-gray-900 font-semibold" id="displayName">Loading...</h3>
                            <p class="text-gray-600 mt-1">Trainer ID: <span id="trainerId">-</span></p>
                            <span id="statusBadge" class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Active</span>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm" id="viewSpecialty">Loading...</span>
                            </div>
                            <div class="flex items-center gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">Joined <span id="joinDate">Loading...</span></span>
                            </div>
                            @if(!session('isEditing'))
                            <button onclick="enableEditing()" id="editProfileBtn" class="w-full mt-4 bg-[#0070FF] hover:bg-[#005FCC] text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
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
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Specialty</label>
                                <input type="text" id="editSpecialty" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Professional Bio</label>
                                <textarea id="editBio" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0070FF] focus:border-transparent"></textarea>
                            </div>
                            <div class="flex gap-2 pt-4">
                                <button type="button" onclick="cancelEditing()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg py-2 flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                                <button type="button" onclick="saveProfile()" class="flex-1 bg-[#0070FF] hover:bg-[#005FCC] text-white rounded-lg py-2 flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Trainer Info & Stats -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Rate Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg text-gray-900 font-semibold mb-4">Rate Information</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-[#0070FF] p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Hourly Rate</p>
                                    <p id="hourlyRate" class="text-2xl text-gray-900 font-bold">$0/hour</p>
                                    <p class="text-xs text-blue-600 mt-1">Only admin can update this value</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Bio -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg text-gray-900 font-semibold mb-4">Professional Bio</h3>
                        <p class="text-gray-700 leading-relaxed" id="viewBio">Loading...</p>
                    </div>

                    <!-- Performance Statistics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg text-gray-900 font-semibold mb-4">Performance Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-[#0070FF] p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Clients</p>
                                        <p id="totalClients" class="text-2xl text-gray-900 font-bold">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-[#00BFA5] p-3 rounded-lg">
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
                        </div>
                    </div>

                    <!-- Experience Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg text-gray-900 font-semibold mb-4">Experience & Location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Years of Experience</p>
                                </div>
                                <p id="displayExperience" class="text-xl text-gray-900 font-semibold">Loading...</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-[#0070FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Location</p>
                                </div>
                                <p id="viewLocation" class="text-xl text-gray-900 font-semibold">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let trainerData = {};

    // Load trainer data from API
    async function loadTrainerData() {
        try {
            const response = await fetch('/trainer/profile/data');
            if (response.ok) {
                trainerData = await response.json();
                updateUI();
            } else {
                console.error('Failed to load trainer data');
            }
        } catch (error) {
            console.error('Error loading trainer data:', error);
        }
    }

    function updateUI() {
        const fullName = `${trainerData.first_name || ''} ${trainerData.middle_name ? trainerData.middle_name + ' ' : ''}${trainerData.last_name || ''}`;
        document.getElementById('displayName').innerText = fullName || 'Trainer User';
        document.getElementById('trainerId').innerText = trainerData.id || '-';
        document.getElementById('viewEmail').innerText = trainerData.email || '-';
        document.getElementById('viewPhone').innerText = trainerData.phone || '-';
        document.getElementById('viewSpecialty').innerText = trainerData.specialization || '-';
        document.getElementById('joinDate').innerText = trainerData.join_date || '-';
        document.getElementById('viewLocation').innerText = trainerData.location || 'Main Branch';
        document.getElementById('viewBio').innerText = trainerData.bio || 'No bio provided yet.';
        document.getElementById('displayExperience').innerText = `${trainerData.experience || 0} years`;
        
        const initials = (trainerData.first_name?.[0] || '') + (trainerData.last_name?.[0] || '');
        document.getElementById('avatarInitials').innerText = initials || 'TC';
        
        // Hourly rate
        const hourlyRate = trainerData.hourly_rate || 0;
        document.getElementById('hourlyRate').innerText = `$${hourlyRate}/hour`;
        
        // Status
        const status = trainerData.status || 'Active';
        const statusBadge = document.getElementById('statusBadge');
        statusBadge.innerText = status;
        statusBadge.className = `mt-4 px-4 py-2 rounded-full text-sm font-medium ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
        
        // Stats
        document.getElementById('totalClients').innerText = trainerData.total_clients || 0;
        document.getElementById('sessionsCompleted').innerText = trainerData.sessions_completed || 0;
        
        // Edit mode values
        document.getElementById('editName').value = fullName;
        document.getElementById('editEmail').value = trainerData.email || '';
        document.getElementById('editPhone').value = trainerData.phone || '';
        document.getElementById('editSpecialty').value = trainerData.specialization || '';
        document.getElementById('editBio').value = trainerData.bio || '';
    }

    function enableEditing() {
        document.getElementById('viewMode').classList.add('hidden');
        document.getElementById('editMode').classList.remove('hidden');
        document.getElementById('editProfileBtn').classList.add('hidden');
    }

    function cancelEditing() {
        document.getElementById('viewMode').classList.remove('hidden');
        document.getElementById('editMode').classList.add('hidden');
        document.getElementById('editProfileBtn').classList.remove('hidden');
        
        const fullName = `${trainerData.first_name || ''} ${trainerData.middle_name ? trainerData.middle_name + ' ' : ''}${trainerData.last_name || ''}`;
        document.getElementById('editName').value = fullName;
        document.getElementById('editEmail').value = trainerData.email || '';
        document.getElementById('editPhone').value = trainerData.phone || '';
        document.getElementById('editSpecialty').value = trainerData.specialization || '';
        document.getElementById('editBio').value = trainerData.bio || '';
    }

    async function saveProfile() {
        const formData = {
            name: document.getElementById('editName').value,
            email: document.getElementById('editEmail').value,
            phone: document.getElementById('editPhone').value,
            specialization: document.getElementById('editSpecialty').value,
            bio: document.getElementById('editBio').value,
        };
        
        try {
            const response = await fetch('/trainer/profile/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });
            
            if (response.ok) {
                alert('Profile updated successfully!');
                await loadTrainerData();
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
        loadTrainerData();
    });
</script>
@endpush
@endsection