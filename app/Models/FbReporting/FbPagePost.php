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

}