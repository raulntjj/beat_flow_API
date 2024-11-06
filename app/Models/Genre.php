<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model{
    protected $fillable = [
        'id',
        'name',
    ];

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
