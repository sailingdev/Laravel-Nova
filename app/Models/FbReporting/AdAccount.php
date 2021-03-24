<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model
{
    use HasFactory;

    protected $casts = [
        'configurations' => 'array'
    ];

    public function websites()
    {
        return $this->hasMany(Website::class);
    }
}
