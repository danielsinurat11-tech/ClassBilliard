@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto animate-in fade-in zoom-in-95 duration-500">
    <div class="mb-8">
        <a href="{{ route('admin.menus.index') }}" class="text-gray-500 hover:text-white flex items-center gap-2 text-sm transition-all mb-4">
            <i class="ri-arrow-left-line"></i> Kembali ke Galeri
        </a>
        <h1 class="text-3xl font-bold text-white">Tambah Hidangan Baru</h1>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-[#111] border border-white/5 rounded-[3rem] p-8 md:p-12 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">Foto Makanan</label>
                    <div class="relative group aspect-square">
                        <input type="file" name="image" id="imgInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        <div id="preview" class="w-full h-full rounded-[2.5rem] bg-white/[0.02] border-2 border-dashed border-white/10 flex flex-col items-center justify-center text-gray-500 group-hover:border-orange-500/50 transition-all overflow-hidden">
                            <i class="ri-image-add-line text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <p class="text-sm font-medium">Klik atau drop gambar</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Nama Menu</label>
                        <input type="text" name="name" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all" placeholder="Misal: Wagyu Steak">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Kategori</label>
                        <select name="category_menu_id" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all appearance-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" class="bg-[#111]">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Harga (Rp)</label>
                        <input type="number" name="price" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-orange-500 text-xl font-bold focus:outline-none focus:border-orange-500/50 transition-all" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] block mb-2">Deskripsi Hidangan</label>
                <textarea name="description" rows="4" class="w-full bg-white/[0.03] border border-white/5 rounded-3xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all leading-relaxed"></textarea>
            </div>

            <div class="mt-12 flex gap-4">
                <button type="submit" class="flex-1 bg-[#ff6b00] hover:bg-orange-600 text-black font-black py-5 rounded-2xl transition-all shadow-xl shadow-orange-500/10 uppercase tracking-widest">
                    Simpan ke Database
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('imgInput').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            document.getElementById('preview').innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover">`
        }
    }
</script>
@endsection