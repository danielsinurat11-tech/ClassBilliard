@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tight">Menu Gallery</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar hidangan visual dan harga operasional.</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" 
           class="bg-[#ff6b00] hover:bg-orange-600 text-black font-bold py-4 px-8 rounded-2xl transition-all flex items-center gap-3 shadow-lg shadow-orange-500/20 active:scale-95">
            <i class="ri-add-circle-fill text-xl"></i> Tambah Menu Baru
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/5 border border-white/10 p-4 rounded-2xl">
            <p class="text-gray-500 text-xs uppercase font-bold tracking-widest">Total Menu</p>
            <p class="text-2xl font-bold text-white">{{ $menus->total() }}</p>
        </div>
        </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($menus as $menu)
        <div class="group bg-[#111] border border-white/5 rounded-[2.5rem] overflow-hidden hover:border-orange-500/30 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
            <div class="relative aspect-[4/3] overflow-hidden">
                <img src="{{ asset($menu->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-transparent opacity-80"></div>
                
                <div class="absolute top-4 left-4">
                    <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-xl border border-white/10 uppercase tracking-widest">
                        {{ $menu->categoryMenu->name }}
                    </span>
                </div>
            </div>

            <div class="p-6 -mt-8 relative z-10">
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($menu->labels ?? [] as $label)
                        <span class="text-[9px] bg-orange-500/10 text-orange-500 px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter border border-orange-500/20">
                            {{ $label }}
                        </span>
                    @endforeach
                </div>

                <h3 class="text-xl font-bold text-white mb-1 truncate">{{ $menu->name }}</h3>
                <p class="text-gray-500 text-xs line-clamp-2 mb-6 h-8 italic">{{ $menu->short_description }}</p>

                <div class="flex justify-between items-center pt-4 border-t border-white/5">
                    <div>
                        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Price</p>
                        <p class="text-lg font-black text-white">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-gray-400 hover:text-white transition-all border border-white/5">
                            <i class="ri-edit-box-line"></i>
                        </a>
                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus hidangan ini?')">
                            @csrf @method('DELETE')
                            <button class="p-3 bg-red-500/5 hover:bg-red-500 text-red-500 hover:text-white rounded-2xl transition-all border border-red-500/10">
                                <i class="ri-delete-bin-7-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $menus->links() }}
    </div>
</div>
@endsection