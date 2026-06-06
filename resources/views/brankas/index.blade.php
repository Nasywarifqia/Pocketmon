@extends('layouts.app')

@section('title', 'Brankas')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Brankas Tabungan</h3>
        <p class="text-sm text-gray-400">Kelola target tabunganmu</p>
    </div>
    <button onclick="openModal('addModal')"
        class="flex items-center gap-2 px-5 py-2.5 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
        <i class="fa-solid fa-plus"></i> Tambah Brankas
    </button>
</div>

{{-- Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($brankas as $item)
        @php
            $percent = $item->target_price > 0
                ? min(100, round(($item->collected_amount / $item->target_price) * 100))
                : 0;
            $priorityColors = [
                'tinggi' => 'bg-red-100 text-red-600',
                'sedang' => 'bg-yellow-100 text-yellow-600',
                'rendah' => 'bg-green-100 text-green-600',
            ];
            $sisa = $item->target_price - $item->collected_amount;
        @endphp
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            {{-- Header Card --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-semibold text-gray-800">{{ $item->item_name }}</h4>
                        @if($item->status == 'tercapai')
                            <span class="px-2 py-0.5 bg-[#D1E7DD] text-green-700 text-xs font-medium rounded-full">✓ Tercapai</span>
                        @endif
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $priorityColors[$item->priority] }}">
                        Prioritas {{ ucfirst($item->priority) }}
                    </span>
                </div>
                <div class="flex gap-2 ml-2">
                    <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', {{ $item->target_price }}, {{ $item->collected_amount }}, '{{ $item->deadline }}', '{{ $item->priority }}', '{{ addslashes($item->description) }}')"
                        class="w-8 h-8 rounded-xl bg-[#E2D9F3] text-purple-600 hover:bg-purple-200 transition flex items-center justify-center">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form method="POST" action="{{ route('brankas.destroy', $item->id) }}"
                        onsubmit="return confirm('Hapus brankas ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 rounded-xl bg-[#F8D7DA] text-red-500 hover:bg-red-200 transition flex items-center justify-center">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs text-gray-400">Progress</span>
                    <span class="text-xs font-bold text-purple-600">{{ $percent }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all"
                        style="width: {{ $percent }}%; background: linear-gradient(90deg, #c9a0dc, #9B72CF);">
                    </div>
                </div>
            </div>

            {{-- Info --}}
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
                    <span class="font-semibold text-red-500">Rp {{ number_format(max(0, $sisa), 0, ',', '.') }}</span>
                </div>
                @if($item->deadline)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Deadline</span>
                        <span class="font-medium text-gray-600">{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}</span>
                    </div>
                @endif
                @if($item->description)
                    <p class="text-xs text-gray-400 pt-2 border-t border-gray-50">{{ $item->description }}</p>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
            <i class="fa-solid fa-vault text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-400 text-sm">Belum ada brankas tabungan</p>
            <button onclick="openModal('addModal')" class="mt-3 text-purple-500 text-sm hover:underline">
                + Buat brankas pertama
            </button>
        </div>
    @endforelse
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md" style="max-height: 85vh; overflow-y: auto;">        
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="font-semibold text-gray-800">Tambah Brankas</h3>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('brankas.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Brankas</label>
                <input type="text" name="item_name" placeholder="Contoh: Brankas Umroh" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Nominal</label>
                <input type="number" name="target_price" placeholder="0" required min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sudah Terkumpul</label>
                <input type="number" name="collected_amount" placeholder="0" min="0"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (opsional)</label>
                <input type="date" name="deadline"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="priority" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                    <option value="">Pilih prioritas</option>
                    <option value="tinggi">🔴 Tinggi</option>
                    <option value="sedang">🟡 Sedang</option>
                    <option value="rendah">🟢 Rendah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" rows="2" placeholder="Keterangan tambahan..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300 resize-none"></textarea>
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                Simpan Brankas
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md" style="max-height: 85vh; overflow-y: auto;">        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="font-semibold text-gray-800">Edit Brankas</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="editForm" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Brankas</label>
                <input type="text" name="item_name" id="editName" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Nominal</label>
                <input type="number" name="target_price" id="editTarget" required min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sudah Terkumpul</label>
                <input type="number" name="collected_amount" id="editCollected" min="0"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (opsional)</label>
                <input type="date" name="deadline" id="editDeadline"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="priority" id="editPriority" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                    <option value="tinggi">🔴 Tinggi</option>
                    <option value="sedang">🟡 Sedang</option>
                    <option value="rendah">🟢 Rendah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" id="editDescription" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300 resize-none"></textarea>
            </div>
            <button type="submit"
                class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                Update Brankas
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
    function openEditModal(id, name, target, collected, deadline, priority, description) {
        document.getElementById('editForm').action = '/brankas/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editTarget').value = target;
        document.getElementById('editCollected').value = collected;
        document.getElementById('editDeadline').value = deadline;
        document.getElementById('editPriority').value = priority;
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