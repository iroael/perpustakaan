<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblt_return_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_detail_id')->constrained('tblt_borrowing_details')->onDelete('cascade');
            $table->date('tanggal_kembali');
            $table->enum('status', ['on_time', 'late'])->default('on_time');
            $table->text('catatan')->nullable();
            $table->foreignId('anggota_id')->constrained('tblm_members')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('tblm_books')->onDelete('cascade');
            $table->integer('denda')->default(0);
            $table->string('petugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblt_return_books');
    }
};
