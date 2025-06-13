<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'tblt_borrowing';

    protected $fillable = [
        'anggota_id',
        'tanggal_pinjam',
    ];

    /**
     * Relasi ke anggota peminjam.
     */
    public function member()
    {
        return $this->belongsTo(Members::class, 'anggota_id');
    }

    /**
     * Relasi ke buku yang dipinjam.
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'tblt_borrowing_details', 'peminjaman_id', 'buku_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Optional: relasi ke detail peminjaman jika ingin query langsung.
     */
    public function details()
    {
        return $this->hasMany(BorrowingDetail::class, 'peminjaman_id');
    }
}
