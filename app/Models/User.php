<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class User extends Authenticatable implements JWTSubject {
    // use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'user',
        'name',
        'last_name',
        'email',
        'password',
        'profile_photo_path',
        'bio',
        'is_private',
    ];

    protected $appends = [
        'profile_photo_temp',
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
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function genres(){
        return $this->belongsToMany(Genre::class, 'user_genres', 'user_id', 'genre_id');
    }

    public function hasGenre($slug){
        return $this->genres()->where('slug', $slug)->exists();
    }

	public function followers() {
	    return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
	}

	public function followed() {
		return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
	}

    public function notifications() {
        return $this->HasMany(Notification::class);
    }

    public function newNotifications(){
        return $this->HasMany(Notification::class)->where('is_read', false)->orderByDesc('created_at');
    }

    public function posts(){
	return $this->hasMany(Post::class, 'user_id')->orderBy('created_at', 'desc'); 
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

    public function projects() {
        return $this->belongsToMany(User::class, 'project_users', 'user_id', 'project_id');
    }

    // Atributos
    public function getProfilePhotoTempAttribute(){
        try {
            return $this->profile_photo_path ? Storage::disk('s3')->temporaryUrl($this->profile_photo_path, Carbon::now()->addDays(7)) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}
