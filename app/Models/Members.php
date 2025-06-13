<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Model untuk tabel anggota dengan nama tblm_members
 * 
 * @property int $id
 * @property string $no_anggota Nomor Anggota
 * @property string $nama Nama Anggota
 * @property string $jenis_kelamin Jenis Kelamin (L/P)
 * @property string $tanggal_lahir Tanggal Lahir
 * @property string $alamat Alamat Anggota
 * @property string|null $photo Foto Anggota (opsional)
 */

class Members extends Model
{
    use HasFactory;

    protected $table = 'tblm_members'; // custom table name dengan prefix tblm_

    protected $fillable = [
        'no_anggota',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'photo',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'anggota_id');
    }
}
