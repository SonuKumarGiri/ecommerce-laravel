@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manage Users</h2>
            <p class="text-sm text-gray-500 mt-1">View and manage system administrators and customers.</p>
        </div>
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="w-full sm:w-64 rounded-lg border-gray-300 text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Search</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium border-b border-gray-100">User</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Email</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Role</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Joined Date</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($users as $user)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $user->is_admin ? 'bg-indigo-100 text-indigo-700' : 'bg-orange-100 text-orange-700' }} flex items-center justify-center font-bold text-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Customer
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-gray-900">No users found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
