@extends('layouts.admin')

@section('title', 'Staff Management')

<<<<<<< HEAD
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
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter {{ $user->role == 'admin' ? 'bg-orange-500/10 text-orange-500' : 'bg-blue-500/10 text-blue-500' }}">
                            {{ $user->role }}
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
=======
@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">

        <!-- HEADER: Minimalist & Functional -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Staff Registry
                </h1>
                <p class="text-xs text-slate-500 dark:text-gray-500 font-medium">
                    Kelola hak akses administrator dan operator sistem operasional.
                </p>
            </div>

            <a href="{{ route('admin.manage-users.create') }}"
                class="inline-flex items-center gap-2 bg-[#fa9a08] hover:bg-orange-600 text-black text-xs font-bold py-2.5 px-5 rounded-md transition-all shadow-sm">
                <i class="ri-user-add-line"></i>
                <span>Add New Staff</span>
            </a>
        </div>

        <!-- TABLE AREA: Border-Based & Professional -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-[0.2em] border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                            Identitas Staff
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-[0.2em] border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                            Role Privilege
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-[0.2em] border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                            Created At
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-[0.2em] border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] text-right">
                            System Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach($users as $user)
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-all">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <!-- Rounded-md instead of rounded-full for Professional look -->
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=fa9a08&color=000&bold=true"
                                        class="w-9 h-9 rounded-md border border-slate-200 dark:border-white/10 object-cover">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-900 dark:text-white leading-none mb-1 group-hover:text-[#fa9a08] transition-colors">
                                            {{ $user->name }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-gray-500 font-medium">
                                            {{ $user->email }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <!-- Minimalist Badge -->
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-wider border {{ $user->role == 'admin' ? 'border-orange-500/30 bg-orange-500/5 text-orange-500' : 'border-blue-500/30 bg-blue-500/5 text-blue-500' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs text-slate-500 dark:text-gray-500 font-medium">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.manage-users.edit', $user) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-white/10 text-slate-400 hover:border-[#fa9a08] hover:text-[#fa9a08] transition-all">
                                        <i class="ri-pencil-line text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.manage-users.destroy', $user) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $user->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-white/10 text-slate-400 hover:border-red-500/50 hover:text-red-500 transition-all">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINATION: Sharp Radius -->
        <div class="pt-8 border-t border-slate-100 dark:border-white/5">
>>>>>>> 4dcc4bed6a4ba3ffc801d6f618a73bb4fab53c46
            {{ $users->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Terminate Access?',
                    text: "User ini tidak akan bisa lagi mengakses sistem administrasi.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#fa9a08',
                    cancelButtonColor: '#1e1e1e',
                    confirmButtonText: 'Yes, Revoke Access',
                    background: document.documentElement.classList.contains('dark') ? '#0A0A0A' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                    customClass: {
                        popup: 'rounded-lg border border-white/5',
                        confirmButton: 'rounded-md text-xs font-bold px-5 py-2.5',
                        cancelButton: 'rounded-md text-xs font-bold px-5 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Handle form submission logic here
                    }
                });
            }
        </script>
    @endpush
@endsection