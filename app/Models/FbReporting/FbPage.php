<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    // public function getIsPublishedAttribute($value)
    // {
    //     return (int) $value == 1 ? 'true' : 'false';
    // } 

    // /**
    //  * Get the user's first name.
    //  *
    //  * @param  string  $value
    //  * @return string
    //  */
    // public function setIsPublishedAttribute($value)
    // {  
    //     return $value == 1 ? 'true' : 'false';
    // } 
}
