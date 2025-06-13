<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Buku
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom kiri: Cover + detail -->
                    <div class="space-y-4">
                        <!-- Cover -->
                        <div class="border p-2 rounded shadow">
                            @if($book->photo)
                                <img src="{{ asset('storage/' . $book->photo) }}" alt="Foto Buku" class="w-full h-auto rounded">
                            @else
                                <div class="w-full h-64 flex items-center justify-center bg-gray-200 text-gray-500 italic">
                                    Tidak ada foto
                                </div>
                            @endif
                        </div>

                        <!-- Informasi detail buku -->
                        <div class="border p-4 rounded">
                            <h3 class="font-semibold text-lg mb-4">Informasi Detail Buku</h3>
                            <div class="mb-2">
                                <span class="font-medium">Judul:</span>
                                <span>{{ $book->judul }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium">Penerbit:</span>
                                <span>{{ $book->penerbit }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium">Dimensi:</span>
                                <span>{{ $book->dimensi ?? '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium">Stock:</span>
                                <span>{{ $book->stock }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom kanan: Preview PDF -->
                    <div class="border p-4 rounded shadow">
                        <h3 class="font-semibold text-lg mb-4">Preview PDF</h3>
                        @if($book->attachment)
                            <iframe src="{{ asset('storage/' . $book->attachment) }}" width="100%" height="600px" class="border rounded"></iframe>
                        @else
                            <p class="text-gray-500 italic">Tidak ada file PDF yang dilampirkan.</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('books.index') }}">
                        <x-secondary-button>Kembali</x-secondary-button>
                    </a>

                    <a href="{{ route('books.edit', $book->id) }}">
                        <x-primary-button>Edit Buku</x-primary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
