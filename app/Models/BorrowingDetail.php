<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    protected $table = 'tblt_borrowing_details';

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'quantity',
        'tanggal_jatuh_tempo',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'peminjaman_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    public function returnBook()
    {
        return $this->hasOne(ReturnBook::class, 'peminjaman_detail_id');
    }




}
