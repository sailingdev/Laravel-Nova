<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rpc extends Model
{
    use HasFactory;

    /**
     * Set table associated with the model.
     *
     * @var string
    */
    protected $table = 'rpc';  
}
