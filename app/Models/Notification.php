<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'is_read',
        'content',
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user()     {
        preg_match('/^(\w+)\s/', $this->content, $matches);

        if (!empty($matches[1])) {
            return User::where('username', $matches[1])->first();
        }

        return null; // Retorna null caso não encontre um username
    }

	    public function getUserFromContent()
    {
        preg_match('/^(\w+)\s/', $this->content, $matches);

        if (!empty($matches[1])) {
            return User::where('user', $matches[1])->first();
        }

        return null; // Retorna null caso não encontre um username
    }
}
