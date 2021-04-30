<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\FbPagePost\SubmitPostRequest;
use App\Labs\FileManager;
use App\Services\FbReporting\FbPagePostService;
use Illuminate\Http\Request;

class FbPagePostsController extends Controller
{
    public function submit(SubmitPostRequest $request, FbPagePostService $fbPagePostService, FileManager $fileManager)
    {   
        if ($request->media !== null && $request->file('media')->isValid()) {
            $uploadImage = $fileManager->uploadFile($request->media, 'fb_posts', 'users/profile_photos', 200, 200, null);
            if (!$uploadImage[0]) {
                return $this->errorResponse('File was not uploaded successfully: ' . $uploadImage[1], [], 400);
            }
            $data['profile_photo'] = $uploadImage[1];
        }
        dd($request->all());
    }
}
