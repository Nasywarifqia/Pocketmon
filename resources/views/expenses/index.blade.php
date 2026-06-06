@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Manajemen Pengeluaran</h3>
        <p class="text-sm text-gray-400">Catat semua pengeluaranmu</p>
    </div>
    <button onclick="openModal('addModal')"
        class="flex items-center gap-2 px-5 py-2.5 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
        <i class="fa-solid fa-plus"></i> Tambah Pengeluaran
    </button>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari pengeluaran..."
            class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">

        <select name="category" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">

        <div class="flex gap-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            <button type="submit" class="px-4 py-2.5 bg-[#F8D7DA] text-pink-700 rounded-xl text-sm font-medium hover:bg-pink-200 transition">
                <i class="fa-solid fa-filter"></i>
            </button>
            <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
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
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Wallet</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-[#F8D7DA] text-pink-700 text-xs font-medium rounded-full">
                            {{ $expense->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $expense->wallet->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $expense->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold text-red-500">
                        -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditModal({{ $expense->id }}, '{{ $expense->date }}', {{ $expense->amount }}, '{{ $expense->category }}', {{ $expense->wallet_id ?? 'null' }}, '{{ addslashes($expense->description) }}')"
                                class="w-8 h-8 rounded-xl bg-[#E2D9F3] text-purple-600 hover:bg-purple-200 transition flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}"
                                onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-xl bg-[#F8D7DA] text-red-500 hover:bg-red-200 transition flex items-center justify-center">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fa-solid fa-inbox text-4xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-400 text-sm">Belum ada data pengeluaran</p>
                        <button onclick="openModal('addModal')" class="mt-3 text-pink-600 text-sm hover:underline">
                            + Tambah sekarang
                        </button>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($expenses->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $expenses->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Pengeluaran</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('expenses.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" name="amount" placeholder="0" required min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet</label>
                <select name="wallet_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="">Pilih wallet</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" rows="2" placeholder="Keterangan tambahan..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300 resize-none"></textarea>
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
                Simpan Pengeluaran
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Edit Pengeluaran</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="editForm" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" id="editDate" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" name="amount" id="editAmount" required min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" id="editCategory" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet</label>
                <select name="wallet_id" id="editWallet" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" id="editDescription" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300 resize-none"></textarea>
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                Update Pengeluaran
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    function openEditModal(id, date, amount, category, walletId, description) {
        document.getElementById('editForm').action = '/expenses/' + id;
        document.getElementById('editDate').value = date;
        document.getElementById('editAmount').value = amount;
        document.getElementById('editCategory').value = category;
        document.getElementById('editWallet').value = walletId;
        document.getElementById('editDescription').value = description;
        openModal('editModal');
    }
    document.querySelectorAll('.fixed').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });
</script>
@endpush