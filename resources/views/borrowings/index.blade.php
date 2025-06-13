<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Daftar Peminjaman
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('borrowings.create') }}">
                    <x-primary-button>Tambah Peminjaman</x-primary-button>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table id="borrowings-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal Pinjam</th>
                            <th class="px-4 py-2 text-left">Nama Peminjam</th>
                            <th class="px-4 py-2 text-left">Jenis Kelamin</th>
                            <th class="px-4 py-2 text-left">Foto</th>
                            <th class="px-4 py-2 text-left">Jumlah Buku</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200">
                        @foreach($borrowings as $borrow)
                            <tr>
                                <td class="px-4 py-2">{{ $borrow->tanggal_pinjam }}</td>
                                <td class="px-4 py-2">{{ $borrow->member->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $borrow->member->jenis_kelamin ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($borrow->member?->photo)
                                        <img src="{{ asset('storage/' . $borrow->member->photo) }}" class="h-12 w-12 rounded-full object-cover" />
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{ $borrow->books->sum(fn($book) => $book->pivot->quantity) }}
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded 
                                        {{ $borrow->status === 'selesai' ? 'bg-green-200 text-green-800' : 
                                        ($borrow->status === 'parsial' ? 'bg-yellow-200 text-yellow-800' : 
                                        'bg-red-200 text-red-800') }}">
                                        {{ ucfirst($borrow->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('borrowings.show', $borrow->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1 rounded">
                                        <x-secondary-button>View</x-secondary-button>
                                    </a>
                                    <x-danger-button onclick="deleteBorrowing({{ $borrow->id }})">Delete</x-danger-button>
                                    

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
            $('#borrowings-table').DataTable();
        });

        function deleteBorrowing(id) {
            if (confirm("Yakin ingin menghapus peminjaman ini?")) {
                fetch(`/borrowings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
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
