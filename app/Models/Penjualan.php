<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penjualan extends Model
{
    protected $fillable = ['TanggalPenjualan', 'TotalHarga', 'UserId'];
}
