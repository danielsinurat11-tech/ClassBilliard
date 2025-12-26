@extends('layouts.admin')

@section('title', 'Edit Menu Details')

@section('content')
    <div class="max-w-5xl mx-auto animate-in fade-in duration-500">

        <!-- HEADER -->
        <div class="flex items-center gap-4 mb-10 border-b border-slate-200 dark:border-white/5 pb-8">
            <a href="{{ route('admin.menus.index') }}"
                class="w-9 h-9 flex items-center justify-center rounded-md border border-slate-200 dark:border-white/10 text-slate-400 hover:text-[#fa9a08] hover:border-[#fa9a08] transition-all">
                <i class="ri-arrow-left-line"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Masterpiece</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Editing: <span class="text-[#fa9a08]">{{ $menu->name }}</span>
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div
                class="mb-8 p-4 bg-red-500/5 border-l-4 border-red-500 rounded-md text-red-500 text-xs font-bold uppercase tracking-wider">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            @csrf
            @method('PUT')

            <!-- LEFT: Visual Control -->
            <div class="lg:col-span-5 space-y-4">
                <label class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Visual
                    Identity</label>
                <div class="relative group aspect-square">
                    <input type="file" name="image" id="imgInput"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    <div id="preview"
                        class="w-full h-full rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden transition-all">
                        <img src="{{ asset($menu->image_path) }}"
                            class="w-full h-full object-cover group-hover:blur-sm transition-all duration-500">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="ri-camera-switch-line text-3xl text-white"></i>
                        </div>
                    </div>
                </div>
                <p
                    class="text-center text-[9px] text-slate-400 dark:text-gray-600 font-bold uppercase tracking-widest italic">
                    *Kosongkan jika tidak ingin mengubah foto</p>
            </div>

            <!-- RIGHT: Data Control -->
            <div class="lg:col-span-7 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-2 md:col-span-2">
                        <label
                            class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Menu
                            Name</label>
                        <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                            class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all font-medium">
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Category</label>
                        <div class="relative">
                            <select name="category_menu_id" required
                                class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all appearance-none font-bold">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('category_menu_id', $menu->category_menu_id) == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Valuation
                            (IDR)</label>
                        <input type="text" id="price_display" value="{{ number_format($menu->price, 0, ',', '.') }}"
                            required
                            class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm font-bold text-[#fa9a08] focus:border-[#fa9a08] outline-none">
                        <input type="hidden" name="price" id="price_real" value="{{ old('price', $menu->price) }}">
                    </div>
                </div>

                <!-- Labels -->
                <div class="space-y-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Current
                        Labels</label>
                    <input type="text" name="labels"
                        value="{{ old('labels', is_array($menu->labels) ? implode(', ', $menu->labels) : $menu->labels) }}"
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all font-medium">
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest ml-1">Gastronomy
                        Description</label>
                    <textarea name="description" rows="4" required
                        class="w-full bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 rounded-md py-3 px-4 text-sm dark:text-white focus:border-[#fa9a08] outline-none transition-all font-medium leading-relaxed">{{ old('description', $menu->description) }}</textarea>
                </div>

                <!-- Actions -->
                <div class="pt-6 flex gap-3">
                    <a href="{{ route('admin.menus.index') }}"
                        class="flex-1 text-center py-3.5 rounded-md border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/5 transition-all">Cancel</a>
                    <button type="submit"
                        class="flex-[2] bg-[#fa9a08] hover:bg-orange-600 text-black font-extrabold py-3.5 rounded-md shadow-lg shadow-orange-500/10 active:scale-[0.98] transition-all uppercase tracking-[0.2em] text-[10px]">
                        Update Masterpiece
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Sama dengan script Create untuk formatting harga & preview image
        const priceDisplay = document.getElementById('price_display');
        const priceReal = document.getElementById('price_real');

        priceDisplay.addEventListener('keyup', function (e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) { let separator = sisa ? '.' : ''; rupiah += separator + ribuan.join('.'); }
            this.value = rupiah;
            priceReal.value = value.replace(/\./g, '');
        });

        document.getElementById('imgInput').onchange = evt => {
            const [file] = evt.target.files
            if (file) { document.getElementById('preview').innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover">`; }
        }
    </script>
@endsection