<div x-data="{ open: false }">
    <!-- Desktop Sidebar - Hidden on mobile, visible on desktop -->
    <nav class="bg-white border-r border-gray-200 h-screen w-64 fixed left-0 top-0 overflow-y-auto z-50 hidden md:block">
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#0070FF] rounded-xl flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z"/>
                        <path d="m2.5 21.5 1.4-1.4"/>
                        <path d="m20.1 3.9 1.4-1.4"/>
                        <path d="M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z"/>
                        <path d="m9.6 14.4 4.8-4.8"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-xl text-gray-800">GymBro</span>
                    <p class="text-xs text-[#0070FF] font-medium">Member Portal</p>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="py-6">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('my-schedule') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('my-schedule') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">My Schedule</span>
            </a>

            <a href="{{ route('membership') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('membership') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span class="font-medium">Membership</span>
            </a>

            <a href="{{ route('available-trainers') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('available-trainers') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-medium">Available Trainers</span>
            </a>

            <a href="{{ route('my-trainer') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('my-trainer') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">My Trainer</span>
            </a>

            <a href="{{ route('payment') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('payment') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span class="font-medium">Payment</span>
            </a>

            <!-- UPDATED: Profile link now points to custom profile page -->
            <a href="{{ route('profile') }}" 
               class="flex items-center gap-3 px-6 py-3 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">Profile</span>
            </a>
        </div>

        <!-- User Info & Logout at Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-200 bg-white">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center shadow-md">
                    <span class="text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="font-medium">Log Out</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Mobile Hamburger Menu Button -->
    <div class="fixed top-4 left-4 z-50 md:hidden">
        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out bg-white shadow-lg">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Overlay Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 -translate-x-full"
         class="fixed inset-0 z-40 md:hidden"
         style="display: none;">
        
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="open = false"></div>
        
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white h-full overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3" @click="open = false">
                    <div class="w-10 h-10 bg-[#0070FF] rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16M8 4v4m8-4v4M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-gray-800">GymBro</span>
                        <p class="text-xs text-[#0070FF] font-medium">Member Portal</p>
                    </div>
                </a>
            </div>
            
            <div class="py-6">
                <a href="{{ route('dashboard') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('my-schedule') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('my-schedule') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">My Schedule</span>
                </a>
                
                <a href="{{ route('membership') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('membership') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="font-medium">Membership</span>
                </a>
                
                <a href="{{ route('available-trainers') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('available-trainers') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Available Trainers</span>
                </a>
                
                <a href="{{ route('my-trainer') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('my-trainer') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">My Trainer</span>
                </a>
                
                <a href="{{ route('payment') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('payment') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="font-medium">Payment</span>
                </a>
                
                <!-- UPDATED: Mobile Profile link -->
                <a href="{{ route('profile') }}" @click="open = false" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('profile') ? 'bg-[#E6F0FF] text-[#0070FF] border-r-4 border-[#0070FF]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>
            </div>
            
            <div class="p-6 border-t border-gray-200 mt-auto">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#0070FF] to-[#005FCC] rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add this style to adjust main content margin -->
<style>
    @media (min-width: 768px) {
        .main-content {
            margin-left: 16rem;
        }
    }
</style>

<!-- Wrap your main page content with this div -->
<div class="main-content">
    <!-- Your existing page content goes here -->
    @yield('content')
</div>