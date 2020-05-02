<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'member_id'
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function message()
    {
        return $this->hasMany('App\Message', 'convo_id', 'id');
    }
}
