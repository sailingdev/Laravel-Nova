<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    public function adText()
    {
        return $this->hasMany(AdText::class);
    }

    public function adLocales()
    {
        return $this->hasMany(AdLocale::class);
    }
}
