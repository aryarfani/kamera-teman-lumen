<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model{
    protected $fillable = [
        'nama', 'alamat', 'phone' , 'email', 'password', 'gambar'
    ];
}