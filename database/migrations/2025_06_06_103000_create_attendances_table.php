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
        Schema::create('attendances', function (Blueprint $table) {
        $table->id('id_attendance');
        $table->unsignedBigInteger('id_ticket');
        $table->enum('status_attd', ['hadir', 'tidak hadir'])->default('tidak hadir');
        $table->timestamps();

        $table->foreign('id_ticket')->references('id_ticket')->on('tickets')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
