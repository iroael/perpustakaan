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
        Schema::create('tblm_books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penerbit');
            $table->string('dimensi')->nullable();
            $table->integer('stock')->default(0);
            $table->string('photo')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblm_books');
    }
};
