<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'nama', 'alamat', 'phone' , 'email', 'password',  'gambar', 'token'
    ];

    public function keranjang()
    {
        return $this->belongsToMany(
            'App\Barang',
            'keranjangs',
            'member_id',
            'barang_id'
        );
    }

    public function riwayat()
    {
        //* many to many
        return $this->belongsToMany(
            'App\Barang',
            'riwayats', // joining table
            'member_id', // foreign key
            'barang_id' // di join ke
        )->withTimestamps()->withPivot('status', 'tanggal_tempo', 'durasi', 'total_biaya');
    }

    public function conversation()
    {
        return $this->hasOne('App\Conversation');
    }
}
