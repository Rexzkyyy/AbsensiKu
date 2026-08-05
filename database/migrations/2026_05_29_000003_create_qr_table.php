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
        Schema::create('qr', function (Blueprint $table) {
            $table->increments('id_qr');
            $table->string('nama_kegiatan', 100);
            $table->string('kode_qr', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();
            $table->time('cek_in');
            $table->time('cek_out')->nullable();
            $table->time('cek_out_jumat')->nullable();
            $table->time('cek_in_minggu')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr');
    }
};
