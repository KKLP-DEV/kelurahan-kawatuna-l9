<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluarModel extends Model
{
    use HasFactory;
    protected $table = 'tb_surat_keluar';
    protected $fillable = [
        'id', 'uuid','id_user', 'nomor_surat', 'tanggal_surat', 'id_tahun', 'id_jenis_surat', 'file_surat', 'tujuan_surat','perihal', 'created_at', 'updated_at'
    ];

    public function tahun()
    {
        return $this->belongsTo(TahunModel::class, 'id_tahun');
    }

    public function jenis_surat()
    {
        return $this->belongsTo(JenisSuratModel::class, 'id_jenis_surat');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getTahun($id_tahun)
    {
        $data = $this->join('tb_tahun', 'tb_surat_keluar.id_tahun', '=', 'tb_tahun.id')
            ->select('tb_tahun.uuid', 'tb_tahun.tahun')
            ->where('tb_surat_keluar.id_tahun', '=', $id_tahun)
            ->first();

        return $data;
    }

    public function getJenisSurat($id_jenis_surat)
    {
        $data = $this->join('tb_jenis_surat', 'tb_surat_keluar.id_jenis_surat', '=', 'tb_jenis_surat.id')
            ->select('tb_jenis_surat.uuid', 'tb_jenis_surat.jenis_surat')
            ->where('tb_surat_keluar.id_jenis_surat', '=', $id_jenis_surat)
            ->first();
        return $data;
    }

    public function getUser($id_user)
    {
        $data = $this->join('users', 'tb_surat_keluar.id_user', '=', 'users.id')
            ->select('users.uuid', 'users.name', 'users.email')
            ->where('tb_surat_keluar.id_user', '=', $id_user)
            ->first();
        return $data;
    }
}
