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
        Schema::create('sertifikats', function (Blueprint $table) {
        $table->id('id_sertifikat');
        $table->unsignedBigInteger('id_users');
        $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
        $table->unsignedBigInteger('id_event');
        $table->foreign('id_event')->references('id_event')->on('events')->onDelete('cascade');
        $table->string('sertifikat_path')->nullable();
        $table->string('kode_verifikasi')->unique();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
