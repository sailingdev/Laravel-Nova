<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdText extends Model
{
    use HasFactory;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
