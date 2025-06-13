<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnBook extends Model
{
    protected $table = 'tblt_return_books';

    protected $fillable = [
        'peminjaman_detail_id',
        'tanggal_kembali',
        'status',
        'catatan',
        'anggota_id',
        'buku_id',
        'denda',
        'petugas',
    ];

    public function borrowingDetail(): BelongsTo
    {
        return $this->belongsTo(BorrowingDetail::class, 'peminjaman_detail_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'anggota_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }
}
