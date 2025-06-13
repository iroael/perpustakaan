<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Pengembalian Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded">

            <!-- Form Pencarian Member -->
            <form action="{{ route('returns.index') }}" method="GET" class="mb-6">
                <label for="anggota_id" class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-200">Pilih Anggota</label>
                <select name="anggota_id" id="anggota_id" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach ($members as $m)
                        <option value="{{ $m->id }}" {{ request('anggota_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
                <x-primary-button class="mt-4">Cari Peminjaman</x-primary-button>
            </form>


            @if (session('success'))
                <div class="mb-4 text-green-600 dark:text-green-300 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($member)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Data Peminjaman: {{ $member->nama }}</h3>

                <table class="min-w-full table-auto border border-gray-300 text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Judul Buku</th>
                            <th class="px-4 py-2 border">Tanggal Pinjam</th>
                            <th class="px-4 py-2 border">Jumlah</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($borrowedBooks as $detail)
                            <tr>
                                <td class="px-4 py-2 border">{{ $detail->book->judul }}</td>
                                <td class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($detail->borrowing->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 border">{{ $detail->quantity }}</td>
                                <td class="px-4 py-2 border">
                                    <x-primary-button class="bg-green-600 hover:bg-green-700" 
                                        onclick="openReturnModal({{ $detail->id }}, '{{ $detail->book->judul }}')">
                                        Kembalikan
                                    </x-primary-button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Tidak ada buku yang sedang dipinjam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif


            @if ($errors->any())
                <div class="mb-4 text-red-600 dark:text-red-400">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Modal -->
            <div id="returnModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Pengembalian Buku</h2>
                    <form id="returnForm" method="POST">
                        @csrf
                        <input type="hidden" name="detail_id" id="detail_id">
                        <div class="mb-4">
                            <label class="block mb-1 text-gray-700 dark:text-gray-300">Judul Buku</label>
                            <input type="text" id="judul_buku" disabled class="w-full border px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-gray-700 dark:text-gray-300">Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" required class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-gray-700 dark:text-gray-300">Denda</label>
                            <input type="number" name="denda" value="0" class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-gray-700 dark:text-gray-300">Catatan</label>
                            <textarea name="catatan" rows="2" class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <!-- <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Simpan</button> -->

                            <x-secondary-button type="button" onclick="closeReturnModal()">Batal</x-secondary-button>
<x-primary-button type="submit">Simpan</x-primary-button>

                        </div>
                    </form>
                </div>
            </div>


            <!-- Tabel Buku akan ditampilkan di returns.result -->
        </div>
    </div>
    @push('scripts')
    <script>
        function openReturnModal(detailId, bookTitle) {
            document.getElementById('detail_id').value = detailId;
            document.getElementById('judul_buku').value = bookTitle;
            const form = document.getElementById('returnForm');
            form.action = `/returns/process/${detailId}`; // Sesuaikan route
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }
    </script>
    @endpush
        
</x-app-layout>
