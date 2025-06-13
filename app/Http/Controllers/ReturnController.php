<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Members;
use App\Models\BorrowingDetail;
use App\Models\ReturnBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $members = Members::has('borrowings.details')->get();

        $borrowedBooks = [];
        $member = null;

        if ($request->filled('anggota_id')) {
            $member = Members::findOrFail($request->anggota_id);
            $borrowedBooks = BorrowingDetail::with(['borrowing', 'book'])
                ->whereHas('borrowing', fn($q) => $q->where('anggota_id', $member->id))
                ->whereDoesntHave('returnBook')
                ->get();
        }

        return view('returns.index', compact('members', 'borrowedBooks', 'member'));
    }

    public function process(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'denda' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $detail = BorrowingDetail::with(['borrowing', 'book'])->findOrFail($id);
            $book = $detail->book;

            $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
            $status = $tanggal_kembali->lte($detail->tanggal_jatuh_tempo) ? 'on_time' : 'late';

            // Buat record pengembalian
            ReturnBook::create([
                'peminjaman_detail_id' => $detail->id,
                'tanggal_kembali' => $tanggal_kembali,
                'status' => $status,
                'catatan' => $request->catatan,
                'anggota_id' => $detail->borrowing->anggota_id,
                'buku_id' => $book->id,
                'denda' => $request->denda ?? 0,
                'petugas' => auth()->user()->name ?? 'Admin',
            ]);

            // Tambah stok buku
            $book->increment('stock', $detail->jumlah ?? 1);

            // 🔁 Cek jumlah detail yang sudah dikembalikan
            $borrowing = $detail->borrowing;
            $totalDetails = $borrowing->details()->count();
            $returnedCount = $borrowing->details()->whereHas('returnBook')->count();

            if ($returnedCount === 0) {
                $borrowing->status = 'dipinjam';
            } elseif ($returnedCount < $totalDetails) {
                $borrowing->status = 'parsial';
            } else {
                $borrowing->status = 'selesai';
            }

            $borrowing->save();

            DB::commit();
            return redirect()->route('returns.index', ['anggota_id' => $borrowing->anggota_id])
                ->with('success', 'Pengembalian berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
