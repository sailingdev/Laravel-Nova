<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPagePost extends Model
{
    use HasFactory;

    public function fbPagePostScheduler()
    {
        return $this->hasMany(FbPagePostScheduler::class);
    }

    /**
     * Get the user's profile photo.
     *
     * @param  string  $value
     * @return string
     */
    public function getMediaAttribute($value)
    {
        return $value !== null ? \App\Labs\FileManager::fetchUploadedFilePath($value, 'fb_posts') : null;
    }

}
