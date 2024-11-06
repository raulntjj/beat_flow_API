<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $fillable = [
        'id',
        'user_id',
        'content',
        'visibility',
        'media_type',
        'media_path',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shareds(){
        return $this->HasMany(SharedPost::class, 'post_id', 'id');
    }

    public function engagements(){
        return $this->HasMany(PostEngagement::class, 'post_id', 'id');
    }   
}
