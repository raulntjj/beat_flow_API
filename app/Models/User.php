<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    // use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'user',
        'name',
        'last_name',
        'email',
        'password',
        'profile_photo',
        'bio',
        'is_private',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function genres(){
        return $this->belongsToMany(Genre::class);
    }

    public function hasGenre($slug){
        return $this->genres()->where('slug', $slug)->exists();
    }

    public function followers(){
        return $this->hasMany(Follow::class, 'followed_id')
        ->with(['follower' => function ($query) {
            $query->select('id', 'name', 'profile_photo');
        }]);
    }
    
    public function followed(){
        return $this->HasMany(Follow::class, 'follower_id')
        ->with(['follower' => function ($query) {
            $query->select('id', 'name', 'profile_photo');
        }]);
    }

    public function notifications() {
        return $this->HasMany(Notification::class);
    }

    public function newNotifications(){
        return $this->HasMany(Notification::class)->where('is_read', false)->orderByDesc('created_at');
    }


    public function hasRole($role){
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission){
        return $this->roles()
            ->whereHas('permissions', function($query) use ($permission) {
                $query->where('name', $permission);
            })->exists();
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}
