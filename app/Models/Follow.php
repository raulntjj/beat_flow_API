<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model{
    protected $fillable = [
        'id',
        'followed_id',
        'follower_id',
    ];

    public function followed(){
        return $this->belongsTo(User::class, 'followed_id');
    }

    public function follower(){
        return $this->belongsTo(User::class, 'follower_id');
    }
}
