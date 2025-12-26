@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto animate-in fade-in zoom-in-95 duration-500">
        <div class="mb-8">
            <a href="{{ route('admin.menus.index') }}"
                class="text-gray-500 hover:text-white flex items-center gap-2 text-sm transition-all mb-4">
                <i class="ri-arrow-left-line"></i> Kembali ke Galeri
            </a>
            <h1 class="text-3xl font-bold text-white">Edit Masterpiece</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui detail hidangan <span
                    class="text-orange-500">"{{ $menu->name }}"</span></p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="bg-[#111] border border-white/5 rounded-[3rem] p-8 md:p-12 shadow-2xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                    <div class="space-y-4">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">Foto Makanan</label>
                        <div class="relative group aspect-square">
                            <input type="file" name="image" id="imgInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            <div id="preview"
                                class="w-full h-full rounded-[2.5rem] bg-white/[0.02] border-2 border-dashed border-white/10 flex flex-col items-center justify-center text-gray-500 group-hover:border-orange-500/50 transition-all overflow-hidden">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ri-image-add-line text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-sm font-medium">Ganti gambar</p>
                                @endif
                            </div>
                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none rounded-[2.5rem]">
                                <i class="ri-camera-switch-line text-3xl text-white"></i>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-600 text-center italic">*Kosongkan jika tidak ingin mengubah foto
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Nama
                                Menu</label>
                            <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                                class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all">
                        </div>

                        <div>
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Kategori</label>
                            <div class="relative">
                                <select name="category_menu_id" required
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all appearance-none">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('category_menu_id', $menu->category_menu_id) == $cat->id) ? 'selected' : '' }} class="bg-[#111]">
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="ri-arrow-down-s-line absolute right-6 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Harga
                                    (Rp)</label>
                                <input type="number" name="price" value="{{ old('price', (int) $menu->price) }}" required
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-orange-500 text-xl font-bold focus:outline-none focus:border-orange-500/50 transition-all">
                            </div>
                            <div>
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Labels</label>
                                <input type="text" name="labels"
                                    value="{{ old('labels', is_array($menu->labels) ? implode(', ', $menu->labels) : $menu->labels) }}"
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all"
                                    placeholder="Best Seller, Pedas">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Deskripsi
                        Hidangan</label>
                    <textarea name="description" rows="4" required
                        class="w-full bg-white/[0.03] border border-white/5 rounded-3xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all leading-relaxed">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="mt-12 flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                        class="flex-[2] bg-white hover:bg-gray-200 text-black font-black py-5 rounded-2xl transition-all shadow-xl uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                        <i class="ri-check-line text-xl"></i>
                        Update Perubahan
                    </button>
                    <a href="{{ route('admin.menus.index') }}"
                        class="flex-1 bg-white/5 hover:bg-white/10 text-white text-center font-black py-5 rounded-2xl transition-all uppercase tracking-widest border border-white/10">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('imgInput').onchange = evt => {
            const [file] = evt.target.files
            if (file) {
                const preview = document.getElementById('preview');
                preview.classList.add('opacity-0');
                setTimeout(() => {
                    preview.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover">`;
                    preview.classList.remove('opacity-0');
                    preview.classList.add('opacity-100');
                }, 100);
            }
        }
    </script>
@endsection