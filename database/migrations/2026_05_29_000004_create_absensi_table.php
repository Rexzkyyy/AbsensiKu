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
        Schema::create('absensi', function (Blueprint $table) {
            $table->increments('id_absensi');
            $table->unsignedInteger('id_qr');
            $table->unsignedInteger('id_user');
            $table->time('absen_cek_in')->nullable();
            $table->time('absen_cek_out')->nullable();
            $table->string('status_cek_out', 100)->nullable();
            $table->string('status_cek_in', 100);
            $table->string('hari_absen', 100);
            $table->time('total_waktu');
            $table->date('created_at')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('id_qr')->references('id_qr')->on('qr')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
