<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Peserta Magang</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl">
        @if(session('success')) <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg">{{ session('success') }}</div> @endif
        
        <form method="POST" action="{{ route('mentor.users.store') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full rounded-lg border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full rounded-lg border-gray-300">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300">
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700">Simpan Peserta</button>
        </form>
    </div>
</x-admin-layout>