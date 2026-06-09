<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - Mode Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA]">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-lg flex flex-col fixed h-full z-50">
        {{-- Logo --}}
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="PocketMon" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-gray-800 text-lg leading-none">PocketMon</h1>
                    <p class="text-xs text-gray-400">Keuangan Pribadi</p>
                </div>
            </div>
        </div>

        {{-- Nav Menu --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#F8D7DA] text-pink-700 font-semibold transition">
                <i class="fa-solid fa-house w-5"></i>
                <span>Dashboard</span>
            </a>
            {{-- Menu lain disabled --}}
            @foreach([
                ['icon' => 'fa-wallet', 'label' => 'Wallet'],
                ['icon' => 'fa-arrow-trend-up', 'label' => 'Pemasukan'],
                ['icon' => 'fa-arrow-trend-down', 'label' => 'Pengeluaran'],
                ['icon' => 'fa-vault', 'label' => 'Brankas'],
                ['icon' => 'fa-clock-rotate-left', 'label' => 'Riwayat'],
                ['icon' => 'fa-chart-pie', 'label' => 'Laporan'],
            ] as $menu)
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 cursor-not-allowed relative group">
                <i class="fa-solid {{ $menu['icon'] }} w-5"></i>
                <span>{{ $menu['label'] }}</span>
                <span class="absolute right-3 text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition">
                    Login dulu
                </span>
            </div>
            @endforeach
        </nav>

        {{-- Guest Info --}}
        <div class="p-2 border-t border-gray-100">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-600">Mode Tamu</p>
                    <p class="text-xs text-gray-400">Data tidak tersimpan</p>
                </div>
                <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-700 transition text-xs font-semibold">
                    Login
                </a>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 ml-64 overflow-y-auto">
        {{-- Topbar --}}
        <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h2 class="font-semibold text-gray-800">Dashboard</h2>
                <p class="text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-[#F8D7DA] text-pink-700 font-semibold rounded-xl text-sm hover:bg-pink-200 transition">
                    <i class="fa-solid fa-user-plus mr-1"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-xl text-sm hover:bg-gray-200 transition">
                    Masuk
                </a>
            </div>
        </div>

        <div class="p-8">
            {{-- Banner Mode Tamu --}}
            <div class="mb-6 px-5 py-4 rounded-2xl flex items-center gap-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <div class="w-10 h-10 rounded-xl bg-yellow-400 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-eye text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-800">Kamu sedang dalam Mode Tamu</p>
                    <p class="text-xs text-yellow-600 mt-0.5">Data yang ditampilkan adalah contoh. Daftar untuk mulai kelola keuanganmu sendiri!</p>
                </div>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-xl text-xs hover:bg-yellow-500 transition flex-shrink-0">
                    Daftar Gratis
                </a>
            </div>

            {{-- Balance Card --}}
            <div class="rounded-3xl p-8 mb-8 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #f4a0b0 0%, #c9a0dc 100%);">
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10 bg-white" style="transform: translate(30%, -30%)"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10 bg-white" style="transform: translate(-30%, 30%)"></div>
                <div class="relative z-10">
                    <p class="text-white/70 text-sm font-medium mb-2">Total Saldo</p>
                    <h2 class="text-5xl font-bold mb-1">Rp {{ number_format($balance, 0, ',', '.') }}</h2>
                    <p class="text-white/60 text-xs mt-2">Data contoh</p>
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="bg-white/20 rounded-2xl p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-arrow-up text-white/80 text-xs"></i>
                                <span class="text-white/70 text-xs">Pemasukan</span>
                            </div>
                            <p class="text-white font-bold text-lg">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/20 rounded-2xl p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-arrow-down text-white/80 text-xs"></i>
                                <span class="text-white/70 text-xs">Pengeluaran</span>
                            </div>
                            <p class="text-white font-bold text-lg">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#D1E7DD] flex items-center justify-center">
                            <i class="fa-solid fa-arrow-trend-up text-green-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-green-600 bg-[#D1E7DD] px-3 py-1 rounded-full">Pemasukan</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Total Pemasukan</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                            <i class="fa-solid fa-arrow-trend-down text-red-500 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-red-500 bg-[#F8D7DA] px-3 py-1 rounded-full">Pengeluaran</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                            <i class="fa-solid fa-piggy-bank text-pink-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-pink-600 bg-[#F8D7DA] px-3 py-1 rounded-full">Brankas</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Brankas Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $activeSavings }} Brankas</h3>
                </div>
            </div>

            {{-- Chart & Savings --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-6">Grafik Keuangan 6 Bulan Terakhir</h3>
                    <canvas id="financeChart" height="100"></canvas>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-6">Brankas Aktif</h3>
                    @foreach($savingsGoals as $item)
                        @php
                            $percent = $item->target_price > 0
                                ? min(100, round(($item->collected_amount / $item->target_price) * 100))
                                : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $item->item_name }}</span>
                                <span class="text-xs text-purple-600 font-semibold">{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all" style="width: {{ $percent }}%; background: linear-gradient(90deg, #c9a0dc, #9B72CF);"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($item->collected_amount, 0, ',', '.') }} / Rp {{ number_format($item->target_price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-semibold text-gray-800">Pemasukan Terbaru</h3>
                    </div>
                    @foreach($recentIncomes as $income)
                        <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                            <div class="w-10 h-10 rounded-xl bg-[#D1E7DD] flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-arrow-up text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700">{{ $income->category }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</p>
                            </div>
                            <span class="text-sm font-semibold text-green-600">+Rp {{ number_format($income->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-semibold text-gray-800">Pengeluaran Terbaru</h3>
                    </div>
                    @foreach($recentExpenses as $expense)
                        <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                            <div class="w-10 h-10 rounded-xl bg-[#F8D7DA] flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-arrow-down text-red-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700">{{ $expense->category }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</p>
                            </div>
                            <span class="text-sm font-semibold text-red-500">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const chartData = @json($chartData);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: chartData.map(d => d.income),
                    backgroundColor: '#D1E7DD',
                    borderColor: '#86CFAC',
                    borderWidth: 2,
                    borderRadius: 8,
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.map(d => d.expense),
                    backgroundColor: '#F8D7DA',
                    borderColor: '#F1AEB5',
                    borderWidth: 2,
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') }
                }
            }
        }
    });
</script>
@endpush
</body>
</html>