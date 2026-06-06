@extends('layouts.app')

@section('title', 'Wallet')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Manajemen Wallet</h3>
        <p class="text-sm text-gray-400">Kelola semua akun keuanganmu</p>
    </div>
    <div class="flex gap-3">
        <button onclick="openModal('transferModal')"
            class="flex items-center gap-2 px-5 py-2.5 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
            <i class="fa-solid fa-right-left"></i> Transfer
        </button>
        <button onclick="openModal('addModal')"
            class="flex items-center gap-2 px-5 py-2.5 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Wallet
        </button>
    </div>
</div>

{{-- Wallet Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @forelse($wallets as $wallet)
        @php
            $icons = [
                'cash'        => ['icon' => 'fa-money-bill-wave', 'bg' => 'bg-[#D1E7DD]', 'text' => 'text-green-600'],
                'bank'        => ['icon' => 'fa-building-columns', 'bg' => 'bg-[#E2D9F3]', 'text' => 'text-purple-600'],
                'credit_card' => ['icon' => 'fa-credit-card', 'bg' => 'bg-[#F8D7DA]', 'text' => 'text-pink-600'],
                'e_wallet'    => ['icon' => 'fa-mobile-screen', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
            ];
            $style = $icons[$wallet->type];
            $typeLabels = [
                'cash'        => 'Cash',
                'bank'        => 'Bank Account',
                'credit_card' => 'Credit Card',
                'e_wallet'    => 'E-Wallet',
            ];
        @endphp
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl {{ $style['bg'] }} flex items-center justify-center">
                        <i class="fa-solid {{ $style['icon'] }} {{ $style['text'] }} text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $wallet->name }}</h4>
                        <span class="text-xs text-gray-400">{{ $typeLabels[$wallet->type] }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="openEditModal({{ $wallet->id }}, '{{ $wallet->name }}', '{{ $wallet->type }}', {{ $wallet->balance }}, '{{ addslashes($wallet->description) }}')"
                        class="w-8 h-8 rounded-xl bg-[#E2D9F3] text-purple-600 hover:bg-purple-200 transition flex items-center justify-center">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form method="POST" action="{{ route('wallets.destroy', $wallet->id) }}"
                        onsubmit="return confirm('Hapus wallet ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 rounded-xl bg-[#F8D7DA] text-red-500 hover:bg-red-200 transition flex items-center justify-center">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
            @if($wallet->description)
                <p class="text-xs text-gray-400 mt-1">{{ $wallet->description }}</p>
            @endif
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
            <i class="fa-solid fa-wallet text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-400 text-sm">Belum ada wallet</p>
            <button onclick="openModal('addModal')" class="mt-3 text-pink-500 text-sm hover:underline">
                + Tambah wallet pertama
            </button>
        </div>
    @endforelse
</div>

{{-- Total Saldo --}}
@if($wallets->count() > 0)
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-400">Total Saldo Semua Wallet</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-1">
                Rp {{ number_format($wallets->sum('balance'), 0, ',', '.') }}
            </h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
            <i class="fa-solid fa-piggy-bank text-pink-600 text-2xl"></i>
        </div>
    </div>
</div>
@endif

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Wallet</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('wallets.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wallet</label>
                <input type="text" name="name" placeholder="Contoh: BCA, Dana, Cash" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Wallet</label>
                <select name="type" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="">Pilih tipe</option>
                    <option value="cash">💵 Cash</option>
                    <option value="bank">🏦 Bank Account</option>
                    <option value="credit_card">💳 Credit Card</option>
                    <option value="e_wallet">📱 E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Awal</label>
                <input type="number" name="balance" placeholder="0" required min="0"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <input type="text" name="description" placeholder="Keterangan tambahan..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
                Simpan Wallet
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Edit Wallet</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="editForm" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wallet</label>
                <input type="text" name="name" id="editName" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Wallet</label>
                <select name="type" id="editType" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="cash">💵 Cash</option>
                    <option value="bank">🏦 Bank Account</option>
                    <option value="credit_card">💳 Credit Card</option>
                    <option value="e_wallet">📱 E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Saldo</label>
                <input type="number" name="balance" id="editBalance" required min="0"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <input type="text" name="description" id="editDescription"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                Update Wallet
            </button>
        </form>
    </div>
</div>

{{-- Modal Transfer --}}
<div id="transferModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Transfer Antar Wallet</h3>
            <button onclick="closeModal('transferModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('wallets.transfer') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Wallet</label>
                <select name="from_wallet_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                    <option value="">Pilih wallet asal</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ke Wallet</label>
                <select name="to_wallet_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                    <option value="">Pilih wallet tujuan</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" name="amount" placeholder="0" required min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <input type="text" name="description" placeholder="Keterangan transfer..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                Transfer Sekarang
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
    function openEditModal(id, name, type, balance, description) {
        document.getElementById('editForm').action = '/wallets/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editType').value = type;
        document.getElementById('editBalance').value = balance;
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