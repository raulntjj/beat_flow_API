<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedPost extends Model{
    protected $fillable = [
        'id',
        'user_id',
        'post_id',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
}
