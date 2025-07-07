<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 flex flex-col">
        <nav class="p-6 text-right text-white">
            <a href="{{ route('login') }}" class="font-semibold hover:underline">Login</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 font-semibold hover:underline">Register</a>
            @endif
        </nav>
        <div class="flex flex-col items-center justify-center flex-1 text-center text-white">
            <h1 class="text-4xl sm:text-6xl font-bold mb-6">Selamat Datang di Sisfo Akademik</h1>
            <p class="mb-8 text-lg">Platform modern untuk pengelolaan data sekolah</p>
            <img src="https://source.unsplash.com/featured/400x300?school" alt="School" class="rounded-lg shadow-lg mb-8" />
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mb-8 text-white animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3" />
            </svg>
            <div>
                <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-indigo-700 font-semibold rounded shadow hover:bg-indigo-50 transition">Mulai</a>
            </div>
        </div>
    </div>
</x-guest-layout>
