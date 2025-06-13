<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Buku
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="createBookForm" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="judul" value="Judul" />
                        <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="penerbit" value="Penerbit" />
                        <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="dimensi" value="Dimensi (misal: 14x20cm)" />
                        <x-text-input id="dimensi" name="dimensi" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="stock" value="Stock" />
                        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="photo" value="Foto Buku (opsional)" />
                        <input id="photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-gray-900 dark:text-white" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="attachment" value="Lampiran PDF (opsional)" />
                        <input id="attachment" name="attachment" type="file" accept="application/pdf" class="mt-1 block w-full text-gray-900 dark:text-white" />
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('books.index') }}">
                            <x-secondary-button type="button">Kembali</x-secondary-button>
                        </a>
                        <x-primary-button type="submit">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('createBookForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch("{{ route('books.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Buku berhasil ditambahkan');
                    window.location.href = "{{ route('books.index') }}";
                } else {
                    alert(data.message || 'Gagal menyimpan data');
                    console.error(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan.');
            });
        });
    </script>
    @endpush
</x-app-layout>
