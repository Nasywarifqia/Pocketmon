<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - Mode Tamu | Brankas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA]">
<div class="flex h-screen overflow-hidden">

    @include('guest.sidebar', ['active' => 'brankas'])

    <main class="flex-1 ml-64 overflow-y-auto">
        <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h2 class="font-semibold text-gray-800">Brankas</h2>
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
                <h3 class="text-lg font-semibold text-gray-800">Brankas Tabungan</h3>
                <button onclick="openModal('addModal')" class="flex items-center gap-2 px-5 py-2.5 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                    <i class="fa-solid fa-plus"></i> Tambah Brankas
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($brankas as $item)
                    @php
                        $item = (object)$item;
                        $percent = $item->target_price > 0 ? min(100, round(($item->collected_amount / $item->target_price) * 100)) : 0;
                        $priorityColors = ['tinggi' => 'bg-red-100 text-red-600', 'sedang' => 'bg-yellow-100 text-yellow-600', 'rendah' => 'bg-green-100 text-green-600'];
                    @endphp
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">{{ $item->item_name }}</h4>
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $priorityColors[$item->priority] }}">
                                    Prioritas {{ ucfirst($item->priority) }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('guest.brankas.destroy', $item->id) }}" onsubmit="return confirm('Hapus brankas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-xl bg-[#F8D7DA] text-red-500 hover:bg-red-200 transition flex items-center justify-center">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-400">Progress</span>
                                <span class="text-xs font-bold text-purple-600">{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full" style="width: {{ $percent }}%; background: linear-gradient(90deg, #c9a0dc, #9B72CF);"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Target</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($item->target_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Terkumpul</span>
                                <span class="font-semibold text-green-600">Rp {{ number_format($item->collected_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Sisa</span>
                                <span class="font-semibold text-red-500">Rp {{ number_format(max(0, $item->target_price - $item->collected_amount), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
                        <i class="fa-solid fa-vault text-5xl text-gray-200 mb-4 block"></i>
                        <p class="text-gray-400 text-sm">Belum ada brankas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md" style="max-height: 85vh; overflow-y: auto;">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="font-semibold text-gray-800">Tambah Brankas</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('guest.brankas.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Brankas</label>
                <input type="text" name="item_name" placeholder="Contoh: Brankas Umroh" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Nominal</label>
                <input type="number" name="target_price" placeholder="0" required min="1" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sudah Terkumpul</label>
                <input type="number" name="collected_amount" placeholder="0" min="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (opsional)</label>
                <input type="date" name="deadline" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="priority" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                    <option value="">Pilih prioritas</option>
                    <option value="tinggi">🔴 Tinggi</option>
                    <option value="sedang">🟡 Sedang</option>
                    <option value="rendah">🟢 Rendah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">Simpan Brankas</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
</body>
</html>