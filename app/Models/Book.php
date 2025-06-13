<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel tblm_books
 *
 * @property int $id
 * @property string $judul
 * @property string $penerbit
 * @property string|null $dimensi
 * @property int $stock
 * @property string|null $photo
 * @property string|null $attachment
 */

class Book extends Model
{
    use HasFactory;

    protected $table = 'tblm_books'; // Custom nama tabel

    protected $fillable = [
        'judul',
        'penerbit',
        'dimensi',
        'stock',
        'photo',
        'attachment',
    ];

    public function borrowings()
    {
        return $this->belongsToMany(Borrowing::class, 'tblt_borrowing_details', 'buku_id', 'peminjaman_id')->withTimestamps();
    }

}
