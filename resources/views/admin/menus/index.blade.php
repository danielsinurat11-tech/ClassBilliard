@extends('layouts.admin')

@section('title', 'Menu Gallery')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">

        <!-- HEADER: Sleek & Aligned -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 dark:border-white/5 pb-8">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Menu Gallery
                </h1>
                <p class="text-xs text-slate-500 dark:text-gray-500 font-medium font-['Plus_Jakarta_Sans']">
                    Manajemen katalog visual dan konfigurasi harga hidangan.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.menus.create') }}"
                    class="inline-flex items-center gap-2 bg-[#fa9a08] hover:bg-orange-600 text-black text-[10px] font-black uppercase tracking-widest py-2.5 px-6 rounded-md transition-all shadow-sm">
                    <i class="ri-add-circle-line text-lg"></i>
                    <span>Add New Menu</span>
                </a>
            </div>
        </div>

        <!-- FILTER BAR: Dynamic Categories -->
        <div
            class="flex items-center gap-6 border-b border-slate-100 dark:border-white/5 pb-2 overflow-x-auto no-scrollbar">
            <button
                class="pb-2 px-1 border-b-2 border-[#fa9a08] text-[#fa9a08] text-[10px] font-black uppercase tracking-widest whitespace-nowrap">
                All Items
            </button>
            @foreach($categories as $category)
                <button
                    class="pb-2 px-1 border-b-2 border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- MAIN GRID: Professional Sleek Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($menus as $menu)
                <div class="group flex flex-col transition-all duration-300">

                    <!-- Image Area: Precise Radius -->
                    <div
                        class="relative aspect-square overflow-hidden rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5">
                        <img src="{{ asset($menu->image_path) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

                        <!-- Floating Price -->
                        <div class="absolute bottom-3 right-3">
                            <div
                                class="bg-[#fa9a08] text-black px-2.5 py-1 rounded text-[10px] font-black shadow-lg uppercase tracking-tighter">
                                IDR {{ number_format($menu->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Category Label -->
                        <div class="absolute top-3 left-3">
                            <span
                                class="px-2 py-0.5 bg-black/60 backdrop-blur-md text-white text-[8px] font-bold uppercase tracking-[0.2em] rounded border border-white/10">
                                {{ $menu->categoryMenu->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Area: Text Focused -->
                    <div class="py-4 space-y-2 flex-1 flex flex-col">
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($menu->labels ?? [] as $label)
                                <span class="text-[8px] font-black text-[#fa9a08] uppercase tracking-widest">{{ $label }}</span>
                                @if(!$loop->last) <span class="text-slate-300 dark:text-gray-700">•</span> @endif
                            @empty
                                <span
                                    class="text-[8px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-widest">Regular</span>
                            @endforelse
                        </div>

                        <h3
                            class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#fa9a08] transition-colors leading-tight">
                            {{ $menu->name }}
                        </h3>

                        <p class="text-[11px] text-slate-500 dark:text-gray-500 font-medium leading-relaxed line-clamp-2">
                            {{ $menu->short_description }}
                        </p>

                        <!-- Actions: Discrete -->
                        <div class="pt-4 flex items-center justify-between mt-auto">
                            <span class="text-[9px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-[0.2em]">Menu
                                Item #{{ $menu->id }}</span>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.menus.edit', $menu) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-white/10 text-slate-400 hover:border-[#fa9a08] hover:text-[#fa9a08] transition-all">
                                    <i class="ri-pencil-line"></i>
                                </a>

                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)"
                                        class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-white/10 text-slate-400 hover:border-red-500 hover:text-red-500 transition-all">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="pt-10 flex justify-center border-t border-slate-100 dark:border-white/5">
            {{ $menus->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(button) {
                showAlert({
                    title: 'Hapus Item?',
                    text: "Data ini akan dihapus permanen dari katalog menu.",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            }
        </script>
    @endpush
@endsection