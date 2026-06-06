<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-[#F8D7DA] flex items-center justify-center mx-auto mb-4 shadow-sm">
                <img src="{{ asset('images/logo.png') }}" alt="PocketMon" class="w-8 h-8 object-contain">
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>
            <p class="text-gray-400 text-sm mt-1">Masuk untuk kelola keuanganmu 💰</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-300"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:border-pink-300 focus:ring-2 focus:ring-pink-100 transition text-sm"
                            placeholder="hello@pocketmon.com" required autofocus>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-pink-500 hover:underline">Lupa Password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-300"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="w-full pl-11 pr-11 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:border-pink-300 focus:ring-2 focus:ring-pink-100 transition text-sm"
                            placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <i class="fa-solid fa-eye text-gray-300 hover:text-gray-500" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-pink-400 focus:ring-pink-200">
                    <label for="remember" class="ml-2 text-sm text-gray-500">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-[#F8D7DA] hover:bg-pink-200 text-pink-700 font-semibold rounded-2xl transition text-sm">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Register Link --}}
        <p class="text-center text-sm text-gray-400 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-pink-500 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>