<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model{
    protected $fillable = [
        'id',
        'name',
    ];
    
    public function users(){
        return $this->belongsToMany(User::class, 'user_genres', 'genre_id', 'user_id');
    }
}
