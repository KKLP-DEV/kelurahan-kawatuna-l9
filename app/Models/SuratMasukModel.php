<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukModel extends Model
{
    use HasFactory;
    protected $table = 'tb_surat_masuk';
    protected $fillable = [
        'id', 'uuid', 'nomor_surat', 'tanggal_surat', 'id_tahun', 'id_jenis_surat', 'file_surat', 'asal_surat', 'created_at', 'updated_at'
    ];

    public function tahun()
    {
        return $this->belongsTo(TahunModel::class, 'id_tahun');
    }

    public function jenis_surat()
    {
        return $this->belongsTo(JenisSuratModel::class, 'id_jenis_surat');
    }

    public function getTahun($id_tahun)
    {
        $data = $this->join('tb_tahun', 'tb_surat_masuk.id_tahun', '=', 'tb_tahun.id')
            ->select('tb_tahun.uuid', 'tb_tahun.tahun')
            ->where('tb_surat_masuk.id_tahun', '=', $id_tahun)
            ->first();

        return $data;
    }

    public function getJenisSurat($id_jenis_surat)
    {
        $data = $this->join('tb_jenis_surat', 'tb_surat_masuk.id_jenis_surat', '=', 'tb_jenis_surat.id')
            ->select('tb_jenis_surat.uuid', 'tb_jenis_surat.jenis_surat')
            ->where('tb_surat_masuk.id_jenis_surat', '=', $id_jenis_surat)
            ->first();
        return $data;
    }
}
