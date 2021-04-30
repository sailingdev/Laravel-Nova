<?php

namespace App\Models\FbReporting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPagePostScheduler extends Model
{
    use HasFactory;

    protected $casts = [
        'page_groups' => 'array'
    ];  

    public function fbPagePost()
    {
        return $this->belongsTo(FbPagePost::class);
    }
}
