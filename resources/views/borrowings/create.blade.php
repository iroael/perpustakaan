<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Peminjaman
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">

                <form id="borrowingForm">
                    @csrf

                    <!-- Anggota -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Anggota</label>
                        <select name="anggota_id" required class="w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal_pinjam" required class="w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Daftar Buku Ditambahkan -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-200">Daftar Buku</label>
                            <x-primary-button type="button" onclick="openBookModal()">+ Tambah Buku</x-primary-button>
                        </div>

                        <table class="min-w-full border border-gray-300 text-sm text-left text-gray-700 dark:text-gray-200" id="selectedBooksTable">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 border">Judul</th>
                                    <th class="px-4 py-2 border">Penerbit</th>
                                    <th class="px-4 py-2 border">Dimensi</th>
                                    <th class="px-4 py-2 border w-32">Jumlah</th>
                                    <th class="px-4 py-2 border w-32">Tanggal Jatuh Tempo</th>
                                    <th class="px-4 py-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- JS akan isi di sini -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button type="submit" id="submitBtn">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="bookModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Pilih Buku</h3>
                <button onclick="closeBookModal()" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
            <table class="min-w-full text-sm border border-gray-300" id="bookListTable">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Judul</th>
                        <th class="px-3 py-2 border">Penerbit</th>
                        <th class="px-3 py-2 border">Stok</th>
                        <th class="px-3 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                    <tr>
                        <td class="border px-3 py-2">{{ $book->judul }}</td>
                        <td class="border px-3 py-2">{{ $book->penerbit }}</td>
                        <td class="border px-3 py-2">{{ $book->stock }}</td>
                        <td class="border px-3 py-2">
                            <button type="button" onclick="addBookToList({{ $book->id }}, '{{ $book->judul }}', '{{ $book->penerbit }}', '{{ $book->dimensi }}')" class="text-blue-600 hover:underline">Tambah</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        const selectedBooks = new Map();

        function openBookModal() {
            document.getElementById('bookModal').classList.remove('hidden');
        }

        function closeBookModal() {
            document.getElementById('bookModal').classList.add('hidden');
        }

        function addBookToList(id, judul, penerbit, dimensi) {
            if (!selectedBooks.has(id)) {
                selectedBooks.set(id, { id, judul, penerbit, dimensi, quantity: 1 });
                renderBookTable();

                console.log(`Buku "${judul}" telah ditambahkan.`);
            }
            closeBookModal();
        }

        function removeBook(id) {
            selectedBooks.delete(id);
            renderBookTable();
        }

        function renderBookTable() {
            // Ambil semua nilai sebelumnya
            const dateInputs = document.querySelectorAll('input[name="tanggal_jatuh_tempo[]"]');
            const quantityInputs = document.querySelectorAll('input[name="book_quantities[]"]');

            // Simpan tanggal jatuh tempo berdasarkan bookId
            const jatuhTempoMap = {};
            const quantityMap = {};

            quantityInputs.forEach(input => {
                const bookId = parseInt(input.dataset.id);
                quantityMap[bookId] = input.value;
            });

            dateInputs.forEach((input, index) => {
                const bookId = parseInt(quantityInputs[index].dataset.id);
                jatuhTempoMap[bookId] = input.value;
            });

            const tbody = document.querySelector('#selectedBooksTable tbody');
            tbody.innerHTML = '';

            selectedBooks.forEach(book => {
                const quantity = quantityMap[book.id] || 1;
                const jatuhTempo = jatuhTempoMap[book.id] || '';

                tbody.innerHTML += `
                    <tr>
                        <td class="border px-4 py-2">
                            ${book.judul}
                            <input type="hidden" name="book_ids[]" value="${book.id}">
                        </td>
                        <td class="border px-4 py-2">${book.penerbit}</td>
                        <td class="border px-4 py-2">${book.dimensi ?? '-'}</td>
                        <td class="border px-4 py-2">
                            <input type="number" name="book_quantities[]" data-id="${book.id}" min="1" value="${quantity}" class="w-full border rounded px-2 py-1" required>
                        </td>
                        <td class="border px-4 py-2">
                            <input type="date" name="tanggal_jatuh_tempo[]" value="${jatuhTempo}" class="w-full border rounded px-2 py-1" required>
                        </td>
                        <td class="border px-4 py-2">
                            <button type="button" onclick="removeBook(${book.id})" class="text-red-600 hover:underline">Hapus</button>
                        </td>
                    </tr>
                `;
            });
        }


        document.getElementById('borrowingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch("{{ route('borrowings.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || "Data peminjaman berhasil disimpan.");
                    window.location.href = "{{ route('borrowings.index') }}";
                } else {
                    alert(data.message || "Terjadi kesalahan.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Gagal menyimpan data.");
            });
        });
    </script>
    @endpush
</x-app-layout>
