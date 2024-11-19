<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model{
    protected $fillable = [
        'id',
        'name',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class, 'permission_roles', 'permission_id', 'role_id');
    }
}

