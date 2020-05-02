<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model{
    protected $fillable = [
        'member_id', 'barang_id'
    ];
    public $timestamps = FALSE;
}