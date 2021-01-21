<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDailyPerf extends Model
{
    use HasFactory;

     /**
     * Set table associated with the model.
     *
     * @var string
    */
    protected $table = 'type_daily_perf';  

    /**
     * A temporary fix: set the supposed "created_at" column used by laravel to point to already existing "date" column in application
     * 
     * @var string
     */
    const CREATED_AT = 'date';
}
