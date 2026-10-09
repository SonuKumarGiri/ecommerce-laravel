<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Dropify CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* Select2 Tailwind Override */
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            line-height: normal !important;
            padding-left: 0.75rem !important;
            padding-right: 2rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 5px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1 !important; /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
            outline: none !important;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #334155; /* slate-700 */
            border-radius: 20px;
        }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb {
            background-color: #475569; /* slate-600 */
        }
        /* Dropify Overrides */
        .dropify-wrapper { border-radius: 0.75rem; }
        .dropify-wrapper .dropify-message p { font-size: 14px; color: #6b7280; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('admin.includes.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 w-full">
            <!-- Header -->
            @include('admin.includes.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 overflow-x-hidden">

                <!-- Page Header / Breadcrumbs -->
                @if(!request()->routeIs('admin.dashboard'))
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                        <nav class="text-sm font-medium text-gray-500 mt-1">
                            <ol class="list-none p-0 flex flex-wrap items-center gap-y-1">
                                <li class="flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
                                </li>
                                @php
                                    $segments = request()->segments();
                                    if(!empty($segments) && strtolower($segments[0]) === 'admin') {
                                        array_shift($segments); // Remove 'admin' from breadcrumb
                                    }
                                @endphp
                                @foreach($segments as $segment)
                                    <li><span class="text-gray-400 mx-2">/</span></li>
                                    <li class="flex items-center">
                                        @if($loop->last)
                                            <span class="text-indigo-600 font-semibold capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                                        @else
                                            <span class="capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

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

    <!-- jQuery & jQuery Validation -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    
    <!-- Dropify JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Automatically add red asterisk to labels of required fields
            $('input[required], select[required], textarea[required]').each(function() {
                let id = $(this).attr('id');
                let label = null;
                
                if (id) {
                    label = $('label[for="' + id + '"]');
                }
                
                if (!label || label.length === 0) {
                    label = $(this).closest('label');
                }
                
                if (!label || label.length === 0) {
                    label = $(this).closest('div').find('label').first();
                }

                if (label && label.length > 0 && label.find('.req-asterisk').length === 0) {
                    // Check if the label already has a hardcoded asterisk to prevent duplicates
                    if (!label.text().includes('*')) {
                        label.append('<span class="req-asterisk text-red-500 font-bold ml-1">*</span>');
                    }
                }
            });

            // Initialize Dropify
            var drEvent = $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove':  'Remove',
                    'error':   'Ooops, something wrong appended.'
                }
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                // If the user clicks remove, append a hidden input so the backend knows
                $('<input>').attr({
                    type: 'hidden',
                    name: 'remove_image',
                    value: '1'
                }).appendTo(element.element.closest('form'));
            });

            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });

            // Apply jQuery validation to POST/PUT/PATCH forms (excluding search GET forms)
            $('form:not([method="GET"])').each(function() {
                $(this).validate({
                    errorElement: 'p',
                    errorClass: 'text-red-500 text-xs mt-1 font-medium',
                    highlight: function(element) {
                        $(element).addClass('border-red-500 focus:border-red-500 focus:ring-red-500').removeClass('border-gray-300');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('border-red-500 focus:border-red-500 focus:ring-red-500').addClass('border-gray-300');
                    },
                    errorPlacement: function(error, element) {
                        if (element.parent().hasClass('relative')) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
