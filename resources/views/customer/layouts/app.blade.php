<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <header class="glass-header sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Hamburger (Mobile) -->
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:text-indigo-600 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    
                    <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        @php
                            $siteName = $siteSettings['site_name'] ?? 'ShopEase';
                            $firstHalf = substr($siteName, 0, ceil(strlen($siteName)/2));
                            $secondHalf = substr($siteName, ceil(strlen($siteName)/2));
                        @endphp
                        {{ $firstHalf }}<span class="text-indigo-600">{{ $secondHalf }}</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} transition-colors">Home</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} transition-colors">Shop</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-2 sm:space-x-4 md:space-x-6">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-slate-600 hover:text-indigo-600 transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white shadow-sm ring-2 ring-white" id="cart-count">{{ \App\Helpers\CartHelper::getCartCount() }}</span>
                    </a>

                    <!-- Notifications Dropdown (Customer) -->
                    @auth
                        <div class="relative" x-data="{ notifyOpen: false }" @click.away="notifyOpen = false">
                            @php
                                $unreadCount = auth()->user()->unreadNotifications->count();
                                $notifications = auth()->user()->notifications()->take(5)->get();
                            @endphp
                            
                            <button @click="notifyOpen = !notifyOpen" class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </button>
                            
                            <div x-show="notifyOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 x-cloak
                                 class="absolute right-0 sm:right-0 top-full mt-2 w-[calc(100vw-2rem)] sm:w-80 bg-white rounded-xl shadow-xl py-2 border border-slate-100 ring-1 ring-black ring-opacity-5 z-50 max-w-sm">
                                
                                <div class="px-4 py-3 flex justify-between items-center border-b border-slate-100">
                                    <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Mark all as read</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-[320px] overflow-y-auto">
                                    @forelse($notifications as $notification)
                                        <a href="{{ route('notifications.mark-read', $notification->id) }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 cursor-pointer flex gap-3 relative {{ $notification->read_at ? 'opacity-70 bg-slate-50/50' : 'bg-white' }}">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm {{ $notification->read_at ? 'font-normal text-slate-600' : 'font-medium text-slate-900' }}">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                                <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notification->read_at)
                                                <div class="w-2 h-2 rounded-full bg-indigo-500 absolute top-4 right-4"></div>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center text-slate-500 text-sm">
                                            No new notifications.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endauth

                    <!-- User Dropdown -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-indigo-600 transition-colors focus:outline-none p-1 rounded-full hover:bg-slate-100 pr-3">
                                 <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 shadow-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block font-semibold">{{ auth()->user()->name }}</span>
                                <svg class="hidden md:block w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl py-2 border border-slate-100 ring-1 ring-black ring-opacity-5">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">Admin Panel</a>
                                @endif
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">My Orders</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">Profile Settings</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden md:flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Sign In</a>
                            <a href="{{ route('register') }}" class="text-sm font-medium bg-slate-900 text-white px-5 py-2.5 rounded-lg hover:bg-slate-800 transition-colors shadow-md shadow-slate-900/20">Sign Up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden border-t border-slate-200 bg-white shadow-lg absolute w-full left-0">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">Home</a>
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('products.*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">Shop</a>
            </div>
            @guest
            <div class="px-4 pb-4 flex gap-3">
                <a href="{{ route('login') }}" class="flex-1 text-center text-sm font-medium text-indigo-600 border border-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50">Sign In</a>
                <a href="{{ route('register') }}" class="flex-1 text-center text-sm font-medium bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800">Sign Up</a>
            </div>
            @endguest
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        @php
                            $siteName = $siteSettings['site_name'] ?? 'ShopEase';
                            $firstHalf = substr($siteName, 0, ceil(strlen($siteName)/2));
                            $secondHalf = substr($siteName, ceil(strlen($siteName)/2));
                        @endphp
                        {{ $firstHalf }}<span class="text-indigo-600">{{ $secondHalf }}</span>
                    </a>
                    <p class="text-slate-500 text-sm max-w-sm">
                        {{ $siteSettings['site_description'] ?? 'Your premium destination for high-quality products. We bring the best directly to your doorstep with secure payments and fast shipping.' }}
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Quick Links</h3>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">Shop Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-indigo-600 transition-colors">View Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Support</h3>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">FAQs</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Shipping & Returns</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-200 mt-12 pt-8 text-center text-sm text-slate-500 flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'ShopEase' }}. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-slate-400 hover:text-slate-600"><span class="sr-only">Facebook</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
                    <a href="#" class="text-slate-400 hover:text-slate-600"><span class="sr-only">Twitter</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Toast Notification -->
    <script>
        window._toastData = {
            @if (session('success'))
                show: true,
                type: 'success',
                message: {!! json_encode(session('success')) !!}
            @elseif (session('error'))
                show: true, type: 'error', message: {!! json_encode(session('error')) !!}
            @elseif (isset($errors) && ($errors->any() || $errors->updatePassword->any()))
                show: true, type: 'error', message: {!! json_encode($errors->updatePassword->any() ? $errors->updatePassword->first() : $errors->first()) !!}
            @elseif (session('status'))
                show: true, type: 'success', message: {!! json_encode(
                    session('status') === 'profile-updated'
                        ? 'Profile updated successfully.'
                        : (session('status') === 'password-updated'
                            ? 'Password updated securely.'
                            : session('status'))
                ) !!}
            @else
                show: false,
                type: 'success',
                message: ''
            @endif
        };
    </script>

    <div x-data="{ show: window._toastData.show, message: window._toastData.message, type: window._toastData.type }" 
        x-init="if (show) setTimeout(() => show = false, 4000)"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 4000)"
        x-show="show" style="display: none;"
        x-transition:enter="transition transform cubic-bezier(0.68,-0.55,0.265,1.55) duration-500"
        x-transition:enter-start="opacity-0 translate-x-full rotate-6 scale-90"
        x-transition:enter-end="opacity-100 translate-x-0 rotate-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0 scale-100"
        x-transition:leave-end="opacity-0 translate-x-full scale-90"
        class="fixed top-20 right-4 sm:right-6 z-[100] flex items-center w-[calc(100%-2rem)] sm:w-80 py-3 px-4 space-x-3 bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100">

        <div class="inline-flex items-center justify-center flex-shrink-0 w-9 h-9 rounded-lg shadow-sm relative overflow-hidden"
            :class="type === 'error' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500'">
            <svg x-show="type === 'success'" class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" style="display: none;" class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="absolute inset-0 bg-current opacity-10 rounded-xl animate-ping"></div>
        </div>

        <div class="text-sm font-medium text-gray-700 flex-1" x-text="message"></div>

        <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
