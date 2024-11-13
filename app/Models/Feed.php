<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model {
    use HasFactory;

    protected $fillable = [
        'post_id',
        'shared_post_id',
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function sharedPost() {
        return $this->belongsTo(SharedPost::class, 'shared_post_id');
    }
}
