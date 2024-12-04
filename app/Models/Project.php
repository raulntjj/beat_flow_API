<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Project extends Model {
    protected $fillable = [
        'name',
        'content',
        'owner_id',
        'cover_path',
        'media_type',
        'media_path',
    ];

    protected $appends = [
        'cover_temp',
        'media_temp'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function participants() {
        return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
    }

    // Atributos
    public function getCoverTempAttribute(){
        try {
            return $this->cover_path ? Storage::disk('s3')->temporaryUrl($this->cover_path, Carbon::now()->addDays(7)) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMediaTempAttribute(){
        try {
            return $this->media_path ? Storage::disk('s3')->temporaryUrl($this->media_path, Carbon::now()->addDays(7)) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
