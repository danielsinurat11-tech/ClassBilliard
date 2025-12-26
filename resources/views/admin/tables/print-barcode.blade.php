<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR - {{ $table->name }}</title>

    {{-- Google Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Remix Icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" />

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            .print-card {
                box-shadow: none !important;
                border: 2px solid #f3f4f6 !important;
                margin: 0 auto !important;
            }

            .bg-amber-custom {
                color: #fa9a08 !important;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0F0F0F;
        }

        .qr-gradient {
            background: linear-gradient(135deg, #fa9a08 0%, #ea580c 100%);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-6">

    <!-- Tombol Navigasi (Hilang saat diprint) -->
    <div class="no-print mb-8 flex gap-4">
        <a href="{{ route('admin.tables.index') }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white hover:bg-white/10 transition-all text-sm font-medium">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
        <button onclick="window.print()"
            class="flex items-center gap-2 px-6 py-2.5 bg-[#fa9a08] text-black rounded-xl font-bold hover:bg-[#e19e2b] transition-all shadow-lg shadow-[#fa9a08]/20">
            <i class="ri-printer-line"></i> Cetak Sekarang
        </button>
    </div>

    <!-- POSTER QR CODE -->
    <div class="print-card bg-[#1A1A1A] w-full max-w-sm rounded-[50px] overflow-hidden shadow-2xl border border-white/5 relative">

        <!-- Header Poster -->
        <div class="qr-gradient p-8 text-center text-black relative">
            <div class="absolute top-0 left-0 w-full h-full opacity-10"
                style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 20px 20px;">
            </div>

            <h1 class="text-2xl font-extrabold italic tracking-tighter relative z-10">
                CLASS<span class="text-white">BILLIARD</span>
            </h1>
        </div>

        <!-- Area QR Code -->
        <div class="bg-white m-8 mt-[-20px] p-8 rounded-[40px] shadow-xl flex flex-col items-center justify-center relative z-20">
            <div class="mb-4">
                @if($table->qrcode && Storage::disk('public')->exists($table->qrcode))
                    <img src="{{ asset('storage/' . $table->qrcode) }}" alt="QR {{ $table->name }}" class="w-[220px] h-[220px] object-contain">
                @else
                    <div class="w-[220px] h-[220px] bg-gray-100 rounded-lg flex flex-col items-center justify-center gap-3">
                        <i class="ri-qr-code-line text-4xl text-gray-400"></i>
                        <p class="text-xs text-gray-500 text-center">QR Code belum di-generate</p>
                        <form action="{{ route('admin.tables.generate-qr', $table->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#fa9a08] text-black text-xs font-bold rounded-lg hover:bg-[#e19e2b] transition-all">
                                Generate QR
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-1">Scan to Order</p>
                <h2 class="text-3xl font-black text-black uppercase tracking-tight italic">
                    {{ $table->name }}
                </h2>
            </div>
        </div>

        <!-- Footer Poster -->
        <div class="px-10 pb-10 text-center">
            <div class="flex items-center justify-center gap-4 mb-6 text-[#fa9a08]">
                <div class="flex flex-col items-center">
                    <i class="ri-restaurant-2-line text-xl"></i>
                    <span class="text-[8px] font-bold uppercase mt-1 text-white/40 tracking-widest">Eatery</span>
                </div>
                <div class="w-px h-6 bg-white/10"></div>
                <div class="flex flex-col items-center">
                    <i class="ri-billiards-line text-xl"></i>
                    <span class="text-[8px] font-bold uppercase mt-1 text-white/40 tracking-widest">Sport</span>
                </div>
                <div class="w-px h-6 bg-white/10"></div>
                <div class="flex flex-col items-center">
                    <i class="ri-wifi-line text-xl"></i>
                    <span class="text-[8px] font-bold uppercase mt-1 text-white/40 tracking-widest">Wifi</span>
                </div>
            </div>

            <div class="p-3 bg-black/40 rounded-2xl border border-white/5">
                <p class="text-[9px] text-slate-500 font-medium leading-relaxed">
                    Silakan scan QR Code di atas untuk melihat menu kami dan melakukan pemesanan langsung dari meja Anda.
                </p>
            </div>

            <p class="text-[8px] mt-6 text-white/20 font-mono tracking-tighter">
                {{ $url }}
            </p>
        </div>
    </div>

    <!-- Info Tambahan -->
    <p class="no-print mt-8 text-slate-500 text-xs text-center max-w-xs leading-relaxed">
        <i class="ri-information-line"></i> Tips: Gunakan kertas stiker glossy atau masukkan ke dalam stand tent card ukuran A5 agar lebih awet.
    </p>


</body>

</html>

