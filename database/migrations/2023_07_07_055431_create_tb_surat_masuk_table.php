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
        Schema::create('tb_surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('nomor_surat');
            $table->string('tanggal_surat');
            $table->foreignId('id_tahun')->constrained('tb_tahun');
            $table->foreignId('id_jenis_surat')->constrained('tb_jenis_surat');
            $table->string('file_surat');
            $table->string('asal_surat');
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
        Schema::dropIfExists('tb_surat_masuk');
    }
};
