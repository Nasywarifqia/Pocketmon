@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Balance Card Utama --}}
<div class="rounded-3xl p-8 mb-8 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #f4a0b0 0%, #c9a0dc 100%);">
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10 bg-white" style="transform: translate(30%, -30%)"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10 bg-white" style="transform: translate(-30%, 30%)"></div>

    <div class="relative z-10">
        <p class="text-white/70 text-sm font-medium mb-2">Total Saldo</p>
        <h2 class="text-5xl font-bold mb-1">Rp {{ number_format($balance, 0, ',', '.') }}</h2>
        <p class="text-white/60 text-xs mt-2">Diperbarui hari ini</p>

        <div class="grid grid-cols-2 gap-4 mt-8">
            <div class="bg-white/20 rounded-2xl p-4 backdrop-blur-sm">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-arrow-up text-white/80 text-xs"></i>
                    <span class="text-white/70 text-xs">Pemasukan</span>
                </div>
                <p class="text-white font-bold text-lg">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/20 rounded-2xl p-4 backdrop-blur-sm">
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
    {{-- Total Pemasukan --}}
    <a href="{{ route('incomes.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-green-200 transition block">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-[#D1E7DD] flex items-center justify-center">
                <i class="fa-solid fa-arrow-trend-up text-green-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-green-600 bg-[#D1E7DD] px-3 py-1 rounded-full">Pemasukan</span>
        </div>
        <p class="text-sm text-gray-400 mb-1">Total Pemasukan</p>
        <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
    </a>

    {{-- Total Pengeluaran --}}
    <a href="{{ route('expenses.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-pink-200 transition block">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                <i class="fa-solid fa-arrow-trend-down text-red-500 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-red-500 bg-[#F8D7DA] px-3 py-1 rounded-full">Pengeluaran</span>
        </div>
        <p class="text-sm text-gray-400 mb-1">Total Pengeluaran</p>
        <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
    </a>

    {{-- Brankas --}}
    <a href="{{ route('brankas.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition block">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                <i class="fa-solid fa-piggy-bank text-pink-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-pink-600 bg-[#F8D7DA] px-3 py-1 rounded-full">Brankas</span>
        </div>
        <p class="text-sm text-gray-400 mb-1">Brankas Aktif</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $activeSavings }} Brankas</h3>
    </a>
</div>


{{-- Chart & Savings --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-6">Grafik Keuangan 6 Bulan Terakhir</h3>
        <canvas id="financeChart" height="100"></canvas>
    </div>

    {{-- Savings Progress --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-6">Brankas Aktif</h3>
        @forelse($savingsGoals->take(4) as $item)
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
                    <div class="bg-[#E2D9F3] h-2 rounded-full transition-all" style="width: {{ $percent }}%; background-color: #9B72CF;"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($item->collected_amount, 0, ',', '.') }} / Rp {{ number_format($item->target_price, 0, ',', '.') }}</p>
            </div>
        @empty
            <div class="text-center py-8">
                <i class="fa-solid fa-piggy-bank text-4xl text-gray-200 mb-3"></i>
                <p class="text-sm text-gray-400">Belum ada target tabungan</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Recent Transactions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Income --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-gray-800">Pemasukan Terbaru</h3>
            <a href="{{ route('incomes.index') }}" class="text-xs text-pink-500 hover:underline">Lihat semua</a>
        </div>
        @forelse($recentIncomes as $income)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-xl bg-[#D1E7DD] flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-arrow-up text-green-600 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $income->category }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</p>
                </div>
                <span class="text-sm font-semibold text-green-600">+Rp {{ number_format($income->amount, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada pemasukan</p>
        @endforelse
    </div>

    {{-- Recent Expense --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-gray-800">Pengeluaran Terbaru</h3>
            <a href="{{ route('expenses.index') }}" class="text-xs text-pink-500 hover:underline">Lihat semua</a>
        </div>
        @forelse($recentExpenses as $expense)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-xl bg-[#F8D7DA] flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-arrow-down text-red-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $expense->category }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</p>
                </div>
                <span class="text-sm font-semibold text-red-500">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada pengeluaran</p>
        @endforelse
    </div>
</div>

@endsection

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
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
@endpush