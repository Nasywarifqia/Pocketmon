@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')

{{-- Filter Bulan --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="flex gap-4 items-end flex-wrap">
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
            <select name="month" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[120px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select name="year" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                @foreach(range(now()->year - 2, now()->year + 1) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-[#F8D7DA] text-pink-700 rounded-xl text-sm font-semibold hover:bg-pink-200 transition">
            <i class="fa-solid fa-filter mr-1"></i> Filter
        </button>
    </form>
</div>

{{-- Stats Cards --}}
{{-- Stats Cards --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <div style="display: flex;">
        <div style="flex: 1; padding-right: 24px;">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-xl bg-[#D1E7DD] flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-up text-green-600 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-green-600">Pemasukan</span>
            </div>
            <p class="text-xs text-gray-400 mb-1">Total Bulan Ini</p>
            <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($monthlyIncomes, 0, ',', '.') }}</h3>
        </div>
        <div style="flex: 1; padding: 0 24px; border-left: 1px solid #f3f4f6;">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-xl bg-[#F8D7DA]
                    <i class="fa-solid fa-arrow-trend-down text-red-500 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-red-500">Pengeluaran</span>
            </div>
            <p class="text-xs text-gray-400 mb-1">Total Bulan Ini</p>
            <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</h3>
        </div>
        <div style="flex: 1; padding-left: 24px; border-left: 1px solid #f3f4f6;">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-xl bg-[#E2D9F3]
                    <i class="fa-solid fa-scale-balanced text-purple-600 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-purple-600">Saldo</span>
            </div>
            <p class="text-xs text-gray-400 mb-1">Saldo Bulan Ini</p>
            <h3 class="text-xl font-bold {{ $monthlyBalance >= 0 ? 'text-gray-800' : 'text-red-500' }}">
                Rp {{ number_format($monthlyBalance, 0, ',', '.') }}
            </h3>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <h3 class="font-semibold text-gray-800 mb-6">Grafik Pemasukan & Pengeluaran (12 Bulan Terakhir)</h3>
    <canvas id="reportChart" height="80"></canvas>
</div>

{{-- Kategori --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Pemasukan per Kategori --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Pemasukan per Kategori</h3>
        @forelse($incomeByCategory as $item)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#D1E7DD] flex items-center justify-center">
                        <i class="fa-solid fa-arrow-up text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-700">{{ $item->category }}</span>
                </div>
                <span class="text-sm font-semibold text-green-600">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Tidak ada data bulan ini</p>
        @endforelse
    </div>

    {{-- Pengeluaran per Kategori --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Pengeluaran per Kategori</h3>
        @forelse($expenseByCategory as $item)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#F8D7DA] flex items-center justify-center">
                        <i class="fa-solid fa-arrow-down text-red-500 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-700">{{ $item->category }}</span>
                </div>
                <span class="text-sm font-semibold text-red-500">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Tidak ada data bulan ini</p>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('reportChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: chartData.map(d => d.income),
                    borderColor: '#86CFAC',
                    backgroundColor: 'rgba(209, 231, 221, 0.3)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.map(d => d.expense),
                    borderColor: '#F1AEB5',
                    backgroundColor: 'rgba(248, 215, 218, 0.3)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
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