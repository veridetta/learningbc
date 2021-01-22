<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiModel extends Model
{
    use HasFactory;
    protected $table = "nilai";
 
    protected $fillable = ['nis','mapel','nama_tes','nilai','kelas'];
}
