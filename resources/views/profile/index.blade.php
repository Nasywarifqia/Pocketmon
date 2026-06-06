@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Avatar & Info --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center gap-6">
            <div class="relative">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                        alt="Foto Profil"
                        class="w-20 h-20 rounded-3xl object-cover">
                @else
                    <div class="w-20 h-20 rounded-3xl bg-[#F8D7DA] flex items-center justify-center">
                        <span class="text-4xl font-bold text-pink-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                <p class="text-gray-400 text-sm mt-1">{{ Auth::user()->email }}</p>
                <p class="text-xs text-gray-300 mt-1">Bergabung sejak {{ Auth::user()->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Edit Profil --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-6">
        <h4 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-user text-pink-400"></i> Edit Profil
        </h4>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="space-y-4">

                {{-- Upload Foto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                class="w-16 h-16 rounded-2xl object-cover">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-[#F8D7DA] flex items-center justify-center">
                                <span class="text-2xl font-bold text-pink-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="photo" accept="image/*" id="photoInput"
                                class="hidden" onchange="previewPhoto(this)">
                            <label for="photoInput"
                                class="cursor-pointer px-4 py-2 bg-[#F8D7DA] text-pink-700 rounded-xl text-sm font-medium hover:bg-pink-200 transition inline-block">
                                <i class="fa-solid fa-camera mr-1"></i> Pilih Foto
                            </label>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG. Maks 2MB</p>
                        </div>
                    </div>
                    <img id="photoPreview" src="" alt="Preview" class="hidden mt-3 w-20 h-20 rounded-2xl object-cover">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-pink-300">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full py-3 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-6">
        <h4 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-lock text-purple-400"></i> Ganti Password
        </h4>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-purple-300">
                </div>
                <button type="submit"
                    class="w-full py-3 bg-[#E2D9F3] hover:bg-purple-200 text-purple-700 font-semibold rounded-2xl transition text-sm">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>

    {{-- Logout --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-3 bg-gray-100 hover:bg-red-50 text-gray-500 hover:text-red-500 font-semibold rounded-2xl transition text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
            </button>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush