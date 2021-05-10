<?php 

namespace App\Services\FbReporting;

use App\Models\FbReporting\FbPagePost;

class FbPagePostService
{
    /**
     * @param array $data
     * 
     * @return array
     */
    public function create(array $data): array
    { 
        $fbPagePost = new FbPagePost;
        $fbPagePost->text = $data['text'];
        $fbPagePost->url = $data['url'];
        $fbPagePost->reference = $data['reference'];
        $fbPagePost->media = $data['media'];
        if ($fbPagePost->save()) {
            return [true, $fbPagePost];
        }
        return [false];
    }

    public function loadLibrary()
    {
        return FbPagePost::latest()->get();
    }

    public function update(array $data, $rowId): array
    { 
        if (FbPagePost::where('id', $rowId)->update($data)) {
            return [true, FbPagePost::where('id', $rowId)->first()];
        }
        return [false];
    }

    /**
     * @param mixed $rowId
     * 
     * @return bool
     */
    public function deleteRow($rowId): bool
    {
       if(FbPagePost::where('id', $rowId)->delete()) {
           return true;
       }
       return false;
    }
}