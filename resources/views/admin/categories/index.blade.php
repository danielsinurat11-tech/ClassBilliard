@extends('layouts.admin')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-700">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Category Master</h1>
                <p class="text-gray-500 text-sm">Atur pengelompokan menu dan urutan tampil di katalog.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-[#111] border border-white/5 rounded-[2.5rem] p-8 sticky top-8 shadow-2xl">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                            <i class="ri-folder-add-line text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Create New</h2>
                    </div>

                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Nama
                                Kategori</label>
                            <input type="text" name="name" required
                                class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all"
                                placeholder="Contoh: Main Course">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Prioritas
                                Urutan</label>
                            <input type="number" name="order_priority" value="0" required
                                class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50 transition-all">
                            <p class="text-[10px] text-gray-600 px-1 italic">*Angka lebih kecil akan muncul lebih awal.</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-[var(--accent)] hover:bg-orange-600 text-black font-black py-5 rounded-2xl transition-all shadow-xl shadow-orange-500/10 uppercase tracking-widest text-xs">
                            Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 gap-4">
                    @foreach($categories as $category)
                        <div
                            class="group bg-[#111] border border-white/5 rounded-3xl p-6 flex items-center justify-between hover:border-white/10 transition-all duration-300 shadow-lg">
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white/[0.02] border border-white/5 flex flex-col items-center justify-center group-hover:bg-orange-500/10 transition-colors">
                                    <span class="text-[10px] font-bold text-gray-600 uppercase">Order</span>
                                    <span
                                        class="text-orange-500 font-black text-lg leading-none">{{ $category->order_priority }}</span>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-xl">{{ $category->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-gray-600 text-xs tracking-wider">/{{ $category->slug }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-800"></span>
                                        <span class="text-gray-600 text-xs">{{ $category->menus->count() }} Items</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->order_priority }}')"
                                    class="p-3 bg-white/5 hover:bg-white/10 rounded-xl text-gray-400 hover:text-white transition-all">
                                    <i class="ri-edit-2-line"></i>
                                </button>

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori ini? Semua menu di dalamnya akan ikut terhapus!')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="p-3 bg-red-500/5 hover:bg-red-500/20 rounded-xl text-gray-500 hover:text-red-500 transition-all">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div
            class="bg-[#111] border border-white/10 w-full max-w-md rounded-[2.5rem] p-10 animate-in zoom-in-95 duration-300">
            <h2 class="text-2xl font-bold text-white mb-8">Edit Category</h2>
            <form id="editForm" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Nama Kategori</label>
                    <input type="text" name="name" id="editName" required
                        class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Prioritas</label>
                    <input type="number" name="order_priority" id="editPriority" required
                        class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-orange-500/50">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-6 py-4 rounded-2xl bg-white/5 text-gray-400 font-bold hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit"
                        class="flex-1 px-6 py-4 rounded-2xl bg-orange-500 text-black font-black hover:bg-orange-600 transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, priority) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            document.getElementById('editName').value = name;
            document.getElementById('editPriority').value = priority;

            form.action = `/admin/categories/${id}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection