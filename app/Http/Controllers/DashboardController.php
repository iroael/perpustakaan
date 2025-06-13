<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Members;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalMembers = Members::count();

        // PostgreSQL-friendly query
        $borrowingsByMonth = Borrowing::selectRaw("TO_CHAR(tanggal_pinjam, 'YYYY-MM') as month, COUNT(*) as count")
            ->where('tanggal_pinjam', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = [];
        $chartData = [];

        // Buat daftar 6 bulan terakhir
        $period = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        foreach ($period as $month) {
            $chartLabels[] = Carbon::parse($month)->translatedFormat('F Y');
            $found = $borrowingsByMonth->firstWhere('month', $month);
            $chartData[] = $found ? $found->count : 0;
        }

        return view('dashboard.index', compact('totalBooks', 'totalMembers', 'chartLabels', 'chartData'));
    }
}
