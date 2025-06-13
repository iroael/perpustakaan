<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data Anggota
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <x-primary-button onclick="openModal()">Tambah Anggota</x-primary-button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table id="members-table" class="min-w-full text-sm text-gray-900 dark:text-white">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">No Anggota</th>
                            <th class="border px-4 py-2">Nama</th>
                            <th class="border px-4 py-2">Jenis Kelamin</th>
                            <th class="border px-4 py-2">Tanggal Lahir</th>
                            <th class="border px-4 py-2">Alamat</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $list)
                            <tr>
                                <td class="border px-4 py-2">{{ $list->no_anggota }}</td>
                                <td class="border px-4 py-2">{{ $list->nama }}</td>
                                <td class="border px-4 py-2">{{ $list->jenis_kelamin }}</td>
                                <td class="border px-4 py-2">{{ $list->tanggal_lahir }}</td>
                                <td class="border px-4 py-2">{{ $list->alamat }}</td>
                                <td class="border px-4 py-2 space-x-2 text-center">

                                    <x-secondary-button onclick="editMember({{ $list }})">Lihat</x-secondary-button>
                                    <x-danger-button onclick="deleteMember({{ $list->id }})">Hapus</x-danger-button>
                                    
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="memberModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-xl transform transition-all scale-95 opacity-0" id="modalContent">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tambah Anggota</h2>

            <form id="memberForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input-label for="no_anggota" value="No Anggota" />
                        <x-text-input id="no_anggota" name="no_anggota" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="nama" value="Nama" />
                        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                        <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                        <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="alamat" value="Alamat" />
                        <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-2">
                    <x-secondary-button type="button" onclick="closeModal()">Batal</x-secondary-button>
                    <x-primary-button type="submit">Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg hidden z-[9999]">
        Data berhasil ditambahkan!
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            $('#members-table').DataTable();
        });

        function openModal() {
            const modal = document.getElementById('memberModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => content.classList.add('scale-100', 'opacity-100'), 10);
        }

        function closeModal() {
            const modal = document.getElementById('memberModal');
            const content = document.getElementById('modalContent');
            content.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('memberForm').reset();
            }, 200);
        }

        function showToast(message, success = true) {
            const toast = document.getElementById('toast');
            toast.classList.remove('hidden');
            toast.textContent = message;
            toast.classList.toggle('bg-green-500', success);
            toast.classList.toggle('bg-red-500', !success);
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        document.getElementById('memberForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            const id = form.dataset.id;
            let url = "{{ route('members.store') }}";
            let method = "POST";

            if (id) {
                url = `/members/${id}`;
                method = "POST"; // gunakan POST dan spoof PUT
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: method,
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Data berhasil disimpan!');
                    form.reset();
                    form.removeAttribute('data-id');
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Gagal menyimpan data.', false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan.', false);
            });
        });

        function editMember(data) {
            console.log('Editing member:', data);
            const member = data;
             // Isi input field dalam modal dengan data yang diklik
            document.getElementById('no_anggota').value = member.no_anggota;
            document.getElementById('nama').value = member.nama;
            document.getElementById('jenis_kelamin').value = member.jenis_kelamin;
            document.getElementById('tanggal_lahir').value = member.tanggal_lahir;
            document.getElementById('alamat').value = member.alamat;

            // Tambahkan ID ke form untuk digunakan saat update
            document.getElementById('memberForm').dataset.id = member.id;

            // Ubah tombol jadi "Update"
            const submitButton = document.querySelector('#memberForm x-primary-button, #memberForm button[type="submit"]');
            if (submitButton) submitButton.textContent = 'Update';

            openModal();
        }

        function deleteMember(id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                fetch(`/members/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Data berhasil dihapus!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal menghapus data.', false);
                    }
                });
            }
        }
    </script>
    @endpush
</x-app-layout>
