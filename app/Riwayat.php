<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $fillable = [
        'member_id', 'barang_id', 'status',  'tanggal_tempo', 'durasi', 'total_biaya'
    ];

    protected $dates = [
        'tanggal_tempo',
    ];
}
