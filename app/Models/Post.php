<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Post extends Model {
    protected $fillable = [
        'id',
        'user_id',
        'content',
        'visibility',
        'media_type',
        'media_path',
    ];

    protected $appends = [
        'media_temp',
        'comments',
        'likes',
    ];

    // Atributos
    public function getMediaTempAttribute(){
        try {
            return $this->media_path ? Storage::disk('s3')->temporaryUrl($this->media_path, Carbon::now()->addDays(7)) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shareds(){
        return $this->HasMany(SharedPost::class, 'post_id', 'id');
    }

    public function getCommentsAttribute() {
        return $this->hasMany(PostEngagement::class, 'post_id', 'id')
        ->where('type', 'comment')
        ->count();
    }

    public function getLikesAttribute() {
        return $this->hasMany(PostEngagement::class, 'post_id', 'id')
        ->where('type', 'like')
        ->count();
    }

    public function engagements(){
        return $this->HasMany(PostEngagement::class, 'post_id', 'id');
    }   
}
