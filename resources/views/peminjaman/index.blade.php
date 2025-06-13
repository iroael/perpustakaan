<x-app-layout>
    <x-slot name="header">Daftar Peminjaman</x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <a href="{{ route('borrowings.create') }}">
            <x-primary-button>Tambah Peminjaman</x-primary-button>
        </a>

        <table class="mt-4 w-full border">
            <thead>
                <tr>
                    <th class="p-2 border">Buku</th>
                    <th class="p-2 border">Peminjam</th>
                    <th class="p-2 border">Tanggal Pinjam</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowings as $borrowing)
                    <tr>
                        <td class="p-2 border">{{ $borrowing->book->judul }}</td>
                        <td class="p-2 border">{{ $borrowing->peminjam }}</td>
                        <td class="p-2 border">{{ $borrowing->tanggal_pinjam }}</td>
                        <td class="p-2 border">{{ ucfirst($borrowing->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
