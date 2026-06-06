@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Riwayat Transaksi</h3>
        <p class="text-sm text-gray-400">Semua pemasukan dan pengeluaran kamu</p>
    </div>
    <div class="text-sm text-gray-500">
        Total <span class="font-semibold text-gray-800">{{ $total }}</span> transaksi
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari transaksi..."
            class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">

        <select name="type" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>Semua Transaksi</option>
            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">

        <div class="flex gap-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            <button type="submit" class="px-4 py-2.5 bg-[#F8D7DA] text-pink-700 rounded-xl text-sm font-medium hover:bg-pink-200 transition">
                <i class="fa-solid fa-filter"></i>
            </button>
            <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Wallet</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Nominal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($transaction['type'] == 'income')
                            <span class="px-3 py-1 bg-[#D1E7DD] text-green-700 text-xs font-medium rounded-full">
                                <i class="fa-solid fa-arrow-up text-xs"></i> Pemasukan
                            </span>
                        @else
                            <span class="px-3 py-1 bg-[#F8D7DA] text-pink-700 text-xs font-medium rounded-full">
                                <i class="fa-solid fa-arrow-down text-xs"></i> Pengeluaran
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction['category'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction['wallet'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction['description'] ?? '-' }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold {{ $transaction['type'] == 'income' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $transaction['type'] == 'income' ? '+' : '-' }}Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fa-solid fa-clock-rotate-left text-4xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-400 text-sm">Belum ada riwayat transaksi</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($total > $perPage)
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-400">
                Menampilkan {{ (($page-1) * $perPage) + 1 }}–{{ min($page * $perPage, $total) }} dari {{ $total }} transaksi
            </p>
            <div class="flex gap-2">
                @if($page > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm hover:bg-gray-200 transition">
                        ← Sebelumnya
                    </a>
                @endif
                @if($page * $perPage < $total)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}"
                        class="px-4 py-2 bg-[#F8D7DA] text-pink-700 rounded-xl text-sm hover:bg-pink-200 transition">
                        Selanjutnya →
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

@endsection