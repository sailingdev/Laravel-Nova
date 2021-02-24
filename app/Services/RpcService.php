<?php

namespace App\Services;

use App\Models\FbReporting\Rpc;

class RpcService
{
    public function countKeyword($keyword)
    {
        return Rpc::where('keyword', $keyword)->count();
    }
}