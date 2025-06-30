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
    Schema::create('registrations', function (Blueprint $table) {
        $table->id('id_registration');
        $table->unsignedBigInteger('id_event');
        $table->unsignedBigInteger('id_users');
        $table->string('nama_lengkap');
        $table->string('no_whatsapp');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->string('instansi');
        $table->integer('participant')->default(1); 
        $table->enum('status_bayar', ['paid', 'pending'])->default('pending');
        $table->enum('status_registrasi', ['pending', 'active', 'complete' ])->default('pending');
        $table->timestamps();
        $table->foreign('id_event')->references('id_event')->on('events')->onUpdate('cascade')->onDelete('cascade');
        $table->foreign('id_users')->references('id_users')->on('users')->onUpdate('cascade')->onDelete('cascade');
        $table->unique(['id_users', 'id_event']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
