<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Tampilkan semua data
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    // Tampilkan form create
    public function create()
    {
        return view('books.create');
    }

    // Simpan data baru via AJAX
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'dimensi'   => 'nullable|string',
            'stock'     => 'required|integer|min:0',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('book_photos', 'public');
        }


        if ($request->hasFile('attachment')) {
            $pdfPath = $request->file('attachment')->store('book_pdfs', 'public');
            $data['attachment'] = $pdfPath;
        }
        $book = Book::create([
            'judul'     => $request->judul,
            'penerbit'  => $request->penerbit,
            'dimensi'   => $request->dimensi,
            'stock'     => $request->stock,
            'photo'     => $photoPath,
            'attachment'=> $pdfPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Buku berhasil ditambahkan', 'data' => $book]);
    }

    // Tampilkan detail (show) ke halaman baru
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }

    // Ambil data buku untuk form edit (AJAX)
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    // Update data via AJAX
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'dimensi'   => 'nullable|string',
            'stock'     => 'required|integer|min:0',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('photo')) {
            if ($book->photo) {
                Storage::disk('public')->delete($book->photo);
            }
            $book->photo = $request->file('photo')->store('book_photos', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($book->attachment) {
                Storage::disk('public')->delete($book->attachment);
            }
            $book->attachment = $request->file('attachment')->store('book_pdfs', 'public');
        }

        $book->update([
            'judul'     => $request->judul,
            'penerbit'  => $request->penerbit,
            'dimensi'   => $request->dimensi,
            'stock'     => $request->stock,
            'photo'     => $book->photo,
        ]);

        return response()->json(['success' => true, 'message' => 'Buku berhasil diperbarui']);
    }

    // Hapus buku via AJAX
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if ($book->photo) {
            Storage::disk('public')->delete($book->photo);
        }
        $book->delete();

        return response()->json(['success' => true, 'message' => 'Buku berhasil dihapus']);
    }
}
