@extends('layouts.admin')

@section('title', 'Kelola Permissions')

@section('content')
<!-- HEADER SECTION -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8 mb-8">
    <div class="space-y-1">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
            <div class="w-10 h-10 rounded-md bg-[#fa9a08]/10 flex items-center justify-center">
                <i class="ri-shield-check-line text-[#fa9a08] text-lg"></i>
            </div>
            Kelola Permissions
        </h1>
        <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">Berikan hak akses lebih kepada user admin dengan mengelola permissions mereka. User kitchen memiliki dashboard terpisah dan tidak dapat dikelola di sini.</p>
    </div>
    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10">
        <i class="ri-group-line text-[#fa9a08]"></i>
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">Total: {{ $users->total() }} Users</span>
    </div>
</div>

<!-- ALERTS -->
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg flex items-start gap-4">
        <i class="ri-checkbox-circle-fill text-emerald-500 text-xl shrink-0 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 shrink-0">
            <i class="ri-close-line"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show"
        class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg flex items-start gap-4">
        <i class="ri-alert-fill text-red-500 text-xl shrink-0 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm font-bold text-red-600 dark:text-red-400">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-red-500 hover:text-red-700 shrink-0">
            <i class="ri-close-line"></i>
        </button>
    </div>
@endif

<!-- USERS LIST CONTAINER -->
@if($users->isEmpty())
    <div class="p-12 text-center border border-slate-200 dark:border-white/5 rounded-lg bg-slate-50 dark:bg-white/[0.02]">
        <i class="ri-user-search-line text-5xl text-slate-300 dark:text-slate-700 mb-4 block"></i>
        <p class="text-sm font-bold text-slate-600 dark:text-slate-400 mb-2">Tidak ada user admin yang ditemukan</p>
        <p class="text-[11px] text-slate-500 dark:text-slate-500">Semua user sudah memiliki permissions diatur atau tidak ada user admin lain yang tersedia</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($users as $user)
            <div class="group p-6 border border-slate-200 dark:border-white/5 rounded-lg hover:border-[#fa9a08]/50 transition-all duration-300 bg-white dark:bg-white/[0.02]">
                <div class="flex items-center justify-between gap-6">
                    {{-- User Info Left --}}
                    <div class="flex items-center gap-4 flex-1">
                        {{-- Avatar --}}
                        <div class="w-12 h-12 bg-[#fa9a08]/10 rounded-md flex items-center justify-center text-[#fa9a08] font-bold text-lg shrink-0 border border-[#fa9a08]/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        {{-- User Details --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest
                                    @if($user->role === 'admin')
                                        bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                    @elseif($user->role === 'kitchen')
                                        bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300
                                    @else
                                        bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300
                                    @endif
                                ">
                                    {{ ucfirst($user->role) }}
                                </span>

                                @php
                                    $permCount = $user->getDirectPermissions()->count();
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                    <i class="ri-shield-line"></i> {{ $permCount }} Permissions
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button Right --}}
                    <a href="{{ route('admin.permissions.manage', $user->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest rounded-md transition-all duration-300 group-hover:shadow-md shrink-0">
                        <i class="ri-edit-line"></i>
                        Kelola
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-8 flex items-center justify-center gap-2">
            {{ $users->links(data: ['view' => 'pagination::tailwind']) }}
        </div>
    @endif
@endif
@endsection
