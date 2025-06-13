<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Detail Peminjaman
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-sm">
            <p><strong>Nama Peminjam:</strong> {{ $borrowing->member->nama }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $borrowing->member->jenis_kelamin }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $borrowing->tanggal_pinjam }}</p>

            <h3 class="mt-4 font-semibold">Buku yang Dipinjam:</h3>
            <table class="min-w-full text-sm mt-2 border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Penerbit</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowing->details as $detail)
            <tr>
                <td class="px-4 py-2 border">{{ $detail->book->judul ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $detail->book->penerbit ?? '-' }}</td>
                <td class="px-4 py-2 border">{{ $detail->quantity }}</td>
                <td class="px-4 py-2 border">
                    @if($detail->returnBook)
                        <span class="text-green-600">Sudah Dikembalikan</span>
                    @else
                        <span class="text-red-600">Belum</span>
                    @endif
                </td>
                <td class="px-4 py-2 border">
                    @if($detail->returnBook)
                        @if($detail->returnBook->status === 'late')
                            <span class="text-red-600">Ya</span>
                        @else
                            <span class="text-green-600">Tidak</span>
                        @endif
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </td>
            </tr>
        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
