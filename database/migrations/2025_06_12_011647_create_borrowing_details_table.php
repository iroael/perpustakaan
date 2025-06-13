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
        Schema::create('tblt_borrowing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('tblt_borrowing')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('tblm_books')->onDelete('cascade');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblt_borrowing_details');
    }
};
