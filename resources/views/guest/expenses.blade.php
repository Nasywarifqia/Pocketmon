<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - Mode Tamu | Pengeluaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA]">
<div class="flex h-screen overflow-hidden">

    @include('guest.sidebar', ['active' => 'expenses'])

    <main class="flex-1 ml-64 overflow-y-auto">
        <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h2 class="font-semibold text-gray-800">Pengeluaran</h2>
                <p class="text-xs text-gray-400">Mode Tamu</p>
            </div>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-[#F8D7DA] text-pink-700 font-semibold rounded-xl text-sm hover:bg-pink-200 transition">
                <i class="fa-solid fa-user-plus mr-1"></i> Daftar Sekarang
            </a>
        </div>

        <div class="p-8">
            {{-- Banner --}}
            <div class="mb-6 px-5 py-4 rounded-2xl flex items-center gap-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <i class="fa-solid fa-eye text-yellow-600 text-xl"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-800">Mode Tamu — Data tidak tersimpan permanen</p>
                    <p class="text-xs text-yellow-600">Daftar untuk simpan data keuanganmu!</p>
                </div>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-xl text-xs hover:bg-yellow-500 transition">Daftar Gratis</a>
            </div>

            @if(session('success'))
                <div class="mb-6 px-4 py-3 bg-[#D1E7DD] text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Manajemen Pengeluaran</h3>
                <button onclick="openModal('addModal')" class="flex items-center gap-2 px-5 py-2.5 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
                    <i class="fa-solid fa-plus"></i> Tambah Pengeluaran
                </button>
            </div>

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
                                <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($expense['date'])->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-[#F8D7DA] text-pink-700 text-xs font-medium rounded-full">{{ $expense['category'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $expense['wallet_name'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $expense['description'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-red-500">-Rp {{ number_format($expense['amount'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('guest.expenses.destroy', $expense['id']) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-xl bg-[#F8D7DA] text-red-500 hover:bg-red-200 transition flex items-center justify-center mx-auto">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <i class="fa-solid fa-inbox text-4xl text-gray-200 mb-3 block"></i>
                                    <p class="text-gray-400 text-sm">Belum ada pengeluaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Pengeluaran</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('guest.expenses.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                <input type="number" name="amount" placeholder="0" required min="1" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet</label>
                <select name="wallet_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    <option value="">Pilih wallet</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet['id'] }}">{{ $wallet['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">Simpan</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
</body>
</html>