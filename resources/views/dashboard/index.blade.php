<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistik Kartu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Jumlah Buku</h3>
                <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalBooks }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Jumlah Anggota</h3>
                <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalMembers }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Peminjaman Bulanan</h3>
                <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ array_sum($chartData) }}</p>
            </div>
        </div>

        <!-- Grafik -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Grafik Peminjaman (6 Bulan Terakhir)</h3>
            <canvas id="borrowChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('borrowChart').getContext('2d');
        const borrowChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</x-app-layout>
