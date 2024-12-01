<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $fillable = [
        'ProdukId',
        'JumlahProduk',
        'user_id',
    ];
}
