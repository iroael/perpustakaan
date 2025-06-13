<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Daftar Buku
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('books.create') }}">
                    <x-primary-button>Tambah Buku</x-primary-button>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table id="books-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Judul</th>
                            <th class="px-4 py-2 text-left">Penerbit</th>
                            <th class="px-4 py-2 text-left">Dimensi</th>
                            <th class="px-4 py-2 text-left">Stock</th>
                            <th class="px-4 py-2 text-left">Foto</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach ($books as $book)
                            <tr>
                                <td class="border px-4 py-2">{{ $book->judul }}</td>
                                <td class="border px-4 py-2">{{ $book->penerbit }}</td>
                                <td class="border px-4 py-2">{{ $book->dimensi }}</td>
                                <td class="border px-4 py-2">{{ $book->stock }}</td>
                                <td class="border px-4 py-2">
                                    @if($book->photo)
                                        <img src="{{ asset('storage/' . $book->photo) }}" alt="Foto Buku" class="h-16 w-auto rounded shadow">
                                    @else
                                        <span class="text-gray-500 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2 space-x-2">
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <x-secondary-button>Lihat / Edit</x-secondary-button>
                                    </a>
                                    <x-danger-button onclick="deleteBook({{ $book->id }})">Hapus</x-danger-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            $('#books-table').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });

        function deleteBook(id) {
            if (confirm("Yakin ingin menghapus buku ini?")) {
                fetch(`/books/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        alert(data.message);
                        location.reload();
                    } else {
                        alert("Gagal menghapus.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi kesalahan.");
                });
            }
        }
    </script>
    @endpush
</x-app-layout>
