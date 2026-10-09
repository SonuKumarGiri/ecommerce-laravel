<!-- Mobile overlay -->
<div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity></div>

<aside x-data="{ activeTooltip: '', tooltipY: 0 }" :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed ? 'lg:w-20' : 'w-64'
    ]" class="fixed inset-y-0 left-0 z-50 bg-slate-900 shadow-xl flex-shrink-0 text-gray-300 h-screen transition-all duration-300 flex flex-col lg:sticky lg:top-0">
    
    <!-- Dynamic Tooltip (Only visible on lg screens when collapsed) -->
    <div x-show="sidebarCollapsed && activeTooltip" x-cloak class="hidden lg:block z-[100]">
        <div :style="`top: ${tooltipY}px`" 
             class="fixed left-20 ml-4 px-3 py-2 bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-xl border border-slate-700 transform -translate-y-1/2 whitespace-nowrap pointer-events-none transition-opacity duration-200">
            <span x-text="activeTooltip"></span>
            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-slate-800"></div>
        </div>
    </div>

    <a href="{{ route('admin.dashboard') }}" :class="sidebarCollapsed ? 'lg:justify-center p-4' : 'p-6'" class="border-b border-slate-800 bg-slate-950 flex items-center gap-3 hover:bg-slate-900 transition-colors">
        <svg class="w-8 h-8 flex-shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        <h2 :class="sidebarCollapsed ? 'lg:hidden' : ''" class="text-2xl font-bold text-white tracking-wider">
            @php
                $siteName = $siteSettings['site_name'] ?? 'AdminPro';
                $firstHalf = substr($siteName, 0, ceil(strlen($siteName)/2));
                $secondHalf = substr($siteName, ceil(strlen($siteName)/2));
            @endphp
            {{ $firstHalf }}<span class="text-indigo-500">{{ $secondHalf }}</span>
        </h2>
    </a>
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto sidebar-scroll">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           @mouseenter="activeTooltip = 'Dashboard'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Dashboard</span>
        </a>
        
        <div class="pt-4 pb-2" 
             @mouseenter="activeTooltip = 'Store Management'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
             @mouseleave="activeTooltip = ''"
             :class="sidebarCollapsed ? 'flex justify-center cursor-help' : ''">
            <p :class="sidebarCollapsed ? 'lg:hidden' : ''" class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Store Management</p>
            <div :class="sidebarCollapsed ? 'hidden lg:block w-8 h-px bg-slate-700 pointer-events-none' : 'hidden'"></div>
        </div>

        <!-- Categories -->
        <a href="{{ route('admin.categories.index') }}" 
           @mouseenter="activeTooltip = 'Categories'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Categories</span>
        </a>
        
        <!-- Products -->
        <a href="{{ route('admin.products.index') }}" 
           @mouseenter="activeTooltip = 'Products'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Products</span>
        </a>
        
        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}" 
           @mouseenter="activeTooltip = 'Orders'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Orders</span>
        </a>
        
        <div class="pt-4 pb-2" 
             @mouseenter="activeTooltip = 'User Management'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
             @mouseleave="activeTooltip = ''"
             :class="sidebarCollapsed ? 'flex justify-center cursor-help' : ''">
            <p :class="sidebarCollapsed ? 'lg:hidden' : ''" class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">User Management</p>
            <div :class="sidebarCollapsed ? 'hidden lg:block w-8 h-px bg-slate-700 pointer-events-none' : 'hidden'"></div>
        </div>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}" 
           @mouseenter="activeTooltip = 'Users'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Users</span>
        </a>

        <div class="pt-4 pb-2" 
             @mouseenter="activeTooltip = 'System'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
             @mouseleave="activeTooltip = ''"
             :class="sidebarCollapsed ? 'flex justify-center cursor-help' : ''">
            <p :class="sidebarCollapsed ? 'lg:hidden' : ''" class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">System</p>
            <div :class="sidebarCollapsed ? 'hidden lg:block w-8 h-px bg-slate-700 pointer-events-none' : 'hidden'"></div>
        </div>

        <!-- Reports -->
        <a href="{{ route('admin.reports.index') }}" 
           @mouseenter="activeTooltip = 'Reports'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Reports</span>
        </a>

        <!-- Settings -->
        <a href="{{ route('admin.settings.index') }}" 
           @mouseenter="activeTooltip = 'Settings'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
           @mouseleave="activeTooltip = ''"
           :class="sidebarCollapsed ? 'justify-center' : ''" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Settings</span>
        </a>
    </nav>
    
    <div class="p-4 border-t border-slate-800 bg-slate-950">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    @mouseenter="activeTooltip = 'Logout'; tooltipY = $event.currentTarget.getBoundingClientRect().top + ($event.currentTarget.offsetHeight / 2)" 
                    @mouseleave="activeTooltip = ''"
                    :class="sidebarCollapsed ? 'justify-center' : ''" 
                    class="flex w-full items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-red-500 hover:text-white transition-all duration-200">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-medium whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>
</aside>
