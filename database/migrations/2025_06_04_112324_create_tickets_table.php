<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id('id_ticket');
        $table->unsignedBigInteger('id_registration');
        $table->enum('status_hadir', ['hadir', 'tidak_hadir'])->default('tidak_hadir');
        $table->string('qr_code'); // bisa berupa string base64 atau filename
        $table->timestamps();

        $table->foreign('id_registration')->references('id_registration')->on('registrations')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
