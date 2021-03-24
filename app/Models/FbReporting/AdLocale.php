<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdLocale extends Model
{
    use HasFactory;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
