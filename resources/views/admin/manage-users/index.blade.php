@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Manajemen Staff</h1>
            <p class="text-gray-500 text-sm">Kelola hak akses administrator dan operator sistem operasional</p>
        </div>
        <a href="{{ route('admin.manage-users.create') }}" 
           class="bg-[var(--accent)] hover:bg-orange-600 text-black font-bold py-3 px-6 rounded-2xl transition-all flex items-center gap-2">
            <i class="ri-user-add-line"></i>
            Tambah Staff
        </a>
    </div>

    <div class="bg-[#111] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/5">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Identitas</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Role Privilege</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Shift</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Dibuat Pada</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($users as $user)
                <tr class="hover:bg-white/[0.01] transition-colors">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=333&color=fff" class="w-10 h-10 rounded-xl">
                            <div>
                                <div class="text-white font-medium">{{ $user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @php
                            $role = $user->getRoleNames()->first() ?? 'no-role';
                            $roleColors = [
                                'super_admin' => 'bg-red-500/10 text-red-500',
                                'admin' => 'bg-orange-500/10 text-orange-500',
                                'kitchen' => 'bg-blue-500/10 text-blue-500',
                                'no-role' => 'bg-gray-500/10 text-gray-500',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter {{ $roleColors[$role] ?? 'bg-gray-500/10 text-gray-500' }}">
                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        @if($user->shift)
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter bg-green-500/10 text-green-500">
                                {{ $user->shift->name }}
                            </span>
                            <div class="text-[10px] text-gray-500 mt-1">
                                {{ $user->shift->start_time->format('H:i') }} - {{ $user->shift->end_time->format('H:i') }} WIB
                            </div>
                        @else
                            <span class="text-gray-500 text-xs">Belum di-assign</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-gray-400 text-sm">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.manage-users.edit', $user) }}" class="p-2 hover:bg-white/5 rounded-lg text-gray-400 hover:text-white transition-all">
                                <i class="ri-edit-2-line"></i>
                            </a>
                            <form action="{{ route('admin.manage-users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Cabut akses user ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 hover:bg-red-500/10 rounded-lg text-gray-400 hover:text-red-500 transition-all">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-white/5">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
