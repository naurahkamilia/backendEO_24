<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
Schema::create('events', function (Blueprint $table) {
        $table->id('id_event');
        $table->string('nama_event');
        $table->string('narasumber');
        $table->enum('kategori_event', ['seminar', 'workshop']);
        $table->enum('jenis_event', ['free', 'paid']);
        $table->date('tanggal_event');
        $table->time('waktu_event');
        $table->string('lokasi');
        $table->text('deskripsi')->nullable();
        $table->text('benefit')->nullable();
        $table->text('catatan')->nullable();
        $table->string('link_wa')->nullable();
        $table->integer('kuota');
        $table->string('gambar');
        $table->integer('harga_event');
        $table->string('template_sertifikat')->nullable();
        $table->unsignedBigInteger('created_by'); // admin
        $table->timestamps();
        $table->foreign('created_by')->references('id_users')->on('users')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations'); 
        Schema::dropIfExists('events');
    }
};
