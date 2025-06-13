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
        Schema::create('tblt_borrowing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('tblm_members')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->string('status')->default('pending');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblt_borrowing');
    }
};
