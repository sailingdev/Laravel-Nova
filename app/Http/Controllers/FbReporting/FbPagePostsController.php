<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\FbPagePost\SubmitPostRequest;
use App\Labs\FileManager;
use App\Services\FbPagePostSchedulerService;
use App\Services\FbReporting\FbPagePostService;
use Illuminate\Http\Request;

class FbPagePostsController extends Controller
{
    public function submit(SubmitPostRequest $request, FbPagePostService $fbPagePostService, FbPagePostSchedulerService $fbPagePostSchedulerService, FileManager $fileManager)
    {   
        if ($request->media !== null && $request->file('media')->isValid()) {
            $uploadImage = $fileManager->uploadFile($request->media, 'media', 'fb_posts');
            if (!$uploadImage[0]) {
                return $this->errorResponse('File was not uploaded successfully: ' . $uploadImage[1], [], 400);
            }
            $data['media'] = $uploadImage[1];
        }
        $newPost = $fbPagePostService->create($data);
        if ($newPost[0] !== true) {
            return $this->errorResponse('An error occured. Please try again');
        }
        if ($request->start_date != null && count($request->page_groups) > 0) {
            $fbPagePostSchedulerService->createSchedule([
                'start_date' => $request->start_date,
                'fb_page_post_id' => $newPost[1]->id,
                'page_groups' => $request->page_groups
            ]);
        }
        return $this->successResponse('New post has been successfully submitted'); 
    }
}
