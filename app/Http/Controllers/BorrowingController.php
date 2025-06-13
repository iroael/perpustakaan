<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Members;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'books'])->latest()->get();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $members = Members::all();
        return view('borrowings.create', compact('books', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:tblm_members,id',
            'tanggal_pinjam' => 'required|date',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:tblm_books,id',
            'book_quantities' => 'required|array',
            'book_quantities.*' => 'integer|min:1',
            'tanggal_jatuh_tempo' => 'required|array',
            'tanggal_jatuh_tempo.*' => 'required|date|after_or_equal:tanggal_pinjam',

        ]);

        // Simpan data peminjaman
        $borrowing = Borrowing::create([
            'anggota_id' => $validated['anggota_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
        ]);

        // Proses relasi buku + quantity
        foreach ($validated['book_ids'] as $index => $bookId) {
            $quantity = $validated['book_quantities'][$index];
            $jatuhTempo = $validated['tanggal_jatuh_tempo'][$index];

            // Simpan ke pivot table
            // $borrowing->books()->attach($bookId, ['quantity' => $quantity]);
            $borrowing->details()->create([
                'buku_id' => $bookId,
                'quantity' => $quantity,
                'tanggal_jatuh_tempo' => $jatuhTempo,
            ]);

            // Kurangi stok buku
            Book::where('id', $bookId)->decrement('stock', $quantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil disimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $borrowing = Borrowing::with(['member', 'books'])->findOrFail($id);
        $borrowing = Borrowing::with(['member', 'details.book', 'details.returnBook'])->findOrFail($id);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        return view('borrowings.edit', compact('borrowing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $validated = $request->validate([
            'tanggal_kembali' => 'required|date',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        // Cek apakah sebelumnya masih dipinjam dan sekarang sudah dikembalikan
        if ($borrowing->status !== 'dikembalikan' && $validated['status'] === 'dikembalikan') {
            $borrowing->book->increment('stock');
        }

        $borrowing->update($validated);

        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $borrowing = Borrowing::with('details')->findOrFail($id);

        // Kembalikan stok buku yang sedang dipinjam
        foreach ($borrowing->details as $detail) {
            $book = Book::find($detail->buku_id);
            if ($book) {
                $book->increment('stock', $detail->quantity);
            }
        }

        // Hapus peminjaman dan detailnya
        $borrowing->details()->delete();
        $borrowing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data peminjaman dihapus.'
        ]);
    }
}
