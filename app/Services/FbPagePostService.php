<?php 

namespace App\Services\FbReporting;

use App\Models\FbReporting\FbPagePost;

class FbPagePostService
{
    public function create(array $data)
    {
        $fbPagePost = new FbPagePost;
        $fbPagePost->text = $data['text'];
        $fbPagePost->url = $data['url'];
        $fbPagePost->reference = $data['reference'];
    }
}