<aside class="w-64 bg-white shadow-lg flex flex-col fixed h-full z-50">
    {{-- Logo --}}
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="PocketMon" class="w-8 h-8 object-contain">
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-lg leading-none">PocketMon</h1>
                <p class="text-xs text-gray-400">Mode Tamu</p>
            </div>
        </div>
    </div>

    {{-- Nav Menu --}}
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $active == 'dashboard' ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
            <i class="fa-solid fa-house w-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('guest.incomes') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $active == 'incomes' ? 'bg-[#D1E7DD] text-green-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
            <i class="fa-solid fa-arrow-trend-up w-5"></i>
            <span>Pemasukan</span>
        </a>
        <a href="{{ route('guest.expenses') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $active == 'expenses' ? 'bg-[#F8D7DA] text-pink-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
            <i class="fa-solid fa-arrow-trend-down w-5"></i>
            <span>Pengeluaran</span>
        </a>
        <a href="{{ route('guest.brankas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $active == 'brankas' ? 'bg-[#E2D9F3] text-purple-700 font-semibold' : 'text-gray-500 hover:bg-gray-50' }} transition">
            <i class="fa-solid fa-vault w-5"></i>
            <span>Brankas</span>
        </a>
    </nav>

    {{-- Guest Info --}}
    <div class="p-2 border-t border-gray-100">
        <div class="px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-200">
            <p class="text-xs font-semibold text-yellow-700 mb-1">⚠️ Mode Tamu</p>
            <p class="text-xs text-yellow-600 mb-2">Data tidak tersimpan permanen</p>
            <div class="flex gap-2">
                <a href="{{ route('register') }}" class="flex-1 text-center py-1.5 bg-[#F8D7DA] text-pink-700 font-semibold rounded-lg text-xs hover:bg-pink-200 transition">Daftar</a>
                <form method="POST" action="{{ route('guest.reset') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-1.5 bg-gray-100 text-gray-500 font-medium rounded-lg text-xs hover:bg-gray-200 transition">Reset</button>
                </form>
            </div>
        </div>
    </div>
</aside>