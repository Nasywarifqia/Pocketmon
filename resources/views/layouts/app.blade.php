<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FA]">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-white shadow-lg flex flex-col fixed h-full z-50 transition-all duration-300 ease-in-out">
            {{-- Logo --}}
            <div class="p-6 border-b border-gray-100">
                {{-- Toggle Button --}}
                <button onclick="toggleSidebar()"
                    id="toggleBtn"
                    class="absolute -right-3 top-8 w-6 h-6 bg-white rounded-full shadow-md border border-gray-100 flex items-center justify-center hover:bg-gray-50 transition z-50">
                    <i class="fa-solid fa-chevron-left text-gray-400 text-xs" id="toggleIcon"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="PocketMon" class="w-8 h-8 object-contain">
                    </div>
                    <div class="hide-on-collapse">
                        <h1 class="font-bold text-gray-800 text-lg leading-none">PocketMon</h1>
                        <p class="text-xs text-gray-400">Keuangan Pribadi</p>
                    </div>
                </div>
            </div>

            {{-- Nav Menu --}}
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto pb-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-house w-5"></i>
                    <span class="hide-on-collapse">Dashboard</span>
                </a>
                <a href="{{ route('wallets.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('wallets.*') ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-wallet w-5"></i>
                    <span class="hide-on-collapse">Wallet</span>
                </a>
                <a href="{{ route('incomes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('incomes.*') ? 'bg-[#D1E7DD] text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-arrow-trend-up w-5"></i>
                    <span class="hide-on-collapse">Pemasukan</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('expenses.*') ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-arrow-trend-down w-5"></i>
                    <span class="hide-on-collapse">Pengeluaran</span>
                </a>
                <a href="{{ route('brankas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('brankas.*') ? 'bg-[#E2D9F3] text-purple-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-vault w-5"></i>
                    <span class="hide-on-collapse">Brankas</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('transactions.*') ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i>
                    <span class="hide-on-collapse">Riwayat</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span class="hide-on-collapse">Laporan</span>
                </a>
            </nav>

            {{-- User Info --}}
            <div class="p-2 border-t border-gray-100">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 flex-1 min-w-0 hover:opacity-80 transition group">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-[#F8D7DA] flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-pink-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0 hide-on-collapse">
                            <p class="text-sm font-semibold text-gray-700 truncate group-hover:text-pink-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hide-on-collapse">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main id="mainContent" class="flex-1 ml-64 overflow-y-auto transition-all duration-300 ease-in-out">
            {{-- Topbar --}}
            <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
                <div>
                    <h2 class="font-semibold text-gray-800">@yield('title')</h2>
                    <p class="text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Halo, <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="p-8">
                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 bg-[#D1E7DD] text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 px-4 py-3 bg-[#F8D7DA] text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>

<script>
    let sidebarOpen = true;
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const icon = document.getElementById('toggleIcon');
        const hideOnCollapse = sidebar.querySelectorAll('.hide-on-collapse');

        if (sidebarOpen) {
            sidebar.style.width = '72px';
            main.style.marginLeft = '72px';
            icon.classList.replace('fa-chevron-left', 'fa-chevron-right');
            hideOnCollapse.forEach(el => el.style.display = 'none');
        } else {
            sidebar.style.width = '256px';
            main.style.marginLeft = '256px';
            icon.classList.replace('fa-chevron-right', 'fa-chevron-left');
            hideOnCollapse.forEach(el => el.style.display = '');
        }
        sidebarOpen = !sidebarOpen;
    }
</script>
@stack('scripts')
</body>
</html>