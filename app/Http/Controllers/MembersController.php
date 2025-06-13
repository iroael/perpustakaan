<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Members::all(); // Mengambil semua data anggota dari tabel tblm_members
        return view('members.index', compact('members')); // Mengirim data members ke view 'members.index'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_anggota'     => 'required|unique:tblm_members,no_anggota',
            'nama'           => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'alamat'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            Members::create([
                'no_anggota'    => $request->no_anggota,
                'nama'          => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat'        => $request->alamat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Anggota berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Members::findOrFail($id);

        $request->validate([
            'no_anggota' => 'required|unique:tblm_members,no_anggota,' . $id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'nullable|string',
        ]);

        $member->update($request->only(['no_anggota', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'alamat']));

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Members::findOrFail($id)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
            ], 500);
        }
    }
}
