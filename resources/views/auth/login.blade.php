<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | Class Billiard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: radial-gradient(circle at center, #1e1e1e 0%, #121212 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="text-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-block p-4 rounded-full bg-red-900/20 mb-4 border border-red-800/30">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold tracking-widest uppercase text-white">Class Billiard</h1>
            <p class="text-gray-400 mt-2 text-sm tracking-wide">Staff Access Portal (Admin & Kitchen)</p>
        </div>

        @if ($errors->any())
            <div id="alert-error" class="mb-6 animate-[shake_0.5s_ease-in-out]">
                <div
                    class="bg-red-950/40 border-l-4 border-red-600 text-red-200 px-4 py-3 rounded-r-xl shadow-2xl flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-bold text-sm uppercase tracking-wider">Akses Gagal</p>
                        <p class="text-xs opacity-80 italic">Email atau password yang Anda masukkan tidak sesuai dengan
                            database kami.</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-6">
                <div
                    class="bg-emerald-950/40 border-l-4 border-emerald-500 text-emerald-200 px-4 py-3 rounded-r-xl shadow-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-xs font-medium">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <div class="glass-effect border border-white/10 p-8 rounded-3xl shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-800 via-red-600 to-red-800"></div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Work
                        Email</label>
                    <div class="relative group">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-red-600/50 focus:border-red-600 transition-all placeholder:text-gray-600"
                            placeholder="nama@classbilliard.com">
                    </div>
                </div>

                <div>
                    <div
                        class="flex justify-between mb-2 ml-1 text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <label>Secure Password</label>
                    </div>
                    <div class="relative group">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" required
                            class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-red-600/50 focus:border-red-600 transition-all placeholder:text-gray-600"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-700 hover:bg-red-600 active:scale-[0.98] text-white font-black py-4 rounded-2xl shadow-lg shadow-red-900/20 transition-all duration-200 uppercase tracking-widest text-sm flex items-center justify-center gap-2 group">
                        Authorize Login
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-gray-500 text-xs mt-8">
            &copy; 2025 Class Billiard. Restricted Access.
        </p>
    </div>

</body>

</html>