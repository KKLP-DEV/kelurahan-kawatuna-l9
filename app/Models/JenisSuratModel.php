<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSuratModel extends Model
{
    use HasFactory;
    protected $table = 'tb_jenis_surat';
    protected $fillable = [
        'id' ,'uuid' , 'jenis_surat' , 'created_at' , 'updated_at'
    ];
}
