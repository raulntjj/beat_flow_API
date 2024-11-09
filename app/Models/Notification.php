<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    use HasFactory;

    protected $fillable = [
        'post_id',
        'type',
        'is_read',
        'content',
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }
}
