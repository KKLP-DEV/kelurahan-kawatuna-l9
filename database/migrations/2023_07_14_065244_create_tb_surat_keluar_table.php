<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('id_user')->constrained('users');
            $table->string('nomor_surat');
            $table->string('tanggal_surat');
            $table->foreignId('id_tahun')->constrained('tb_tahun');
            $table->foreignId('id_jenis_surat')->constrained('tb_jenis_surat');
            $table->string('file_surat');
            $table->string('tujuan_surat');
            $table->text('perihal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_surat_keluar');
    }
};
