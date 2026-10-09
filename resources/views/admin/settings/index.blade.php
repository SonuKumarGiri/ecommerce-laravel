@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-gray-800">General Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage global settings for your eCommerce platform.</p>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Site Name -->
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name <span class="text-red-500">*</span></label>
                    <input type="text" name="site_name" id="site_name" required value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                </div>

                <!-- Contact Email -->
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Support Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" id="contact_email" required value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="support@example.com">
                </div>
                
                <!-- Phone Number -->
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="+1 (555) 123-4567">
                </div>

                <!-- Store Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Physical Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="123 Commerce St, Tech City...">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>
                
                <!-- Currency Symbol -->
                <div>
                    <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol <span class="text-red-500">*</span></label>
                    <input type="text" name="currency_symbol" id="currency_symbol" required value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '₹') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="₹ or $">
                </div>
                
                <!-- Site Description -->
                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                    <textarea name="site_description" id="site_description" rows="2"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="Your Premium eCommerce Solution">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <!-- Store Logo -->
                    <div>
                        <label for="store_logo" class="block text-sm font-medium text-gray-700 mb-2">Store Logo</label>
                        <input type="file" name="store_logo" id="store_logo" class="dropify" data-height="120" accept="image/*"
                            @if(isset($settings['store_logo'])) data-default-file="{{ asset($settings['store_logo']) }}" @endif>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label for="store_favicon" class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                        <input type="file" name="store_favicon" id="store_favicon" class="dropify" data-height="120" accept="image/png, image/x-icon, image/ico"
                            @if(isset($settings['store_favicon'])) data-default-file="{{ asset($settings['store_favicon']) }}" @endif>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
