<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunModel extends Model
{
    use HasFactory;
    protected $table = 'tb_tahun';
    protected $fillable = [
        'id' , 'uuid' , 'tahun' , 'created_at' , 'updated_at'
    ];
}
