<header class="bg-white border-b border-gray-200 sticky top-0 z-40 flex-shrink-0">
    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
        <div class="flex items-center">
            <button @click="window.innerWidth < 1024 ? sidebarOpen = !sidebarOpen : sidebarCollapsed = !sidebarCollapsed" class="p-2 mr-4 text-gray-500 rounded-md hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 block">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <!-- Notifications -->
            <div class="relative" x-data="{ notifyOpen: false }" @click.away="notifyOpen = false">
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                    $notifications = auth()->user()->notifications()->take(5)->get();
                @endphp
                
                <button @click="notifyOpen = !notifyOpen" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Badge -->
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </button>
                
                <div x-show="notifyOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-[-40px] sm:right-0 top-full mt-2 w-72 sm:w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden max-w-[100vw]"
                     style="display: none;">
                    
                    <!-- Header -->
                    <div class="px-4 py-3 flex justify-between items-center border-b border-gray-100 bg-white">
                        <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                        @if($unreadCount > 0)
                            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium">Mark all as read</button>
                            </form>
                        @endif
                    </div>

                    <!-- Notification Items -->
                    <div class="max-h-[320px] overflow-y-auto">
                        @forelse($notifications as $notification)
                            <a href="{{ route('admin.notifications.mark-read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 cursor-pointer flex gap-3 relative {{ $notification->read_at ? 'opacity-70 bg-gray-50/50' : 'bg-white' }}">
                                <div class="flex-shrink-0 mt-1">
                                    @if(str_contains($notification->type, 'NewOrderNotification'))
                                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm {{ $notification->read_at ? 'font-normal text-gray-600' : 'font-medium text-gray-900' }}">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 rounded-full bg-indigo-500 absolute top-4 right-4"></div>
                                @endif
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                No new notifications.
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Footer -->
                    <div class="border-t border-gray-100 bg-gray-50">
                        <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-2.5 text-xs text-center font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            View all notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-50 focus:outline-none transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 hidden md:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100" 
                     x-transition:enter-start="transform opacity-0 scale-95" 
                     x-transition:enter-end="transform opacity-100 scale-100" 
                     x-transition:leave="transition ease-in duration-75" 
                     x-transition:leave-start="transform opacity-100 scale-100" 
                     x-transition:leave-end="transform opacity-0 scale-95" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100 z-50" 
                     style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-gray-100 text-sm">
                        <p class="text-gray-500">Signed in as</p>
                        <p class="font-medium text-gray-900 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        My Profile
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
