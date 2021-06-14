<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class SubmittedKeyword extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Set the keyword.
     *
     * @param  string  $value
     * @return void
     */
    public function setKeywordAttribute($value)
    {
        $this->attributes['keyword'] = strtolower($value);
    }

    /**
     * Get the keyword
     *
     * @param  string  $value
     * @return string
     */
    public function getKeywordAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * Set the feed
     *
     * @param  string  $value
     * @return void
     */
    public function setFeedAttribute($value)
    {
        $this->attributes['feed'] = strtolower($value);
    }

    /**
     * Get the feed
     *
     * @param  string  $value
     * @return string
     */
    public function getFeedAttribute($value)
    {
        return strtolower($value);
    }
}
