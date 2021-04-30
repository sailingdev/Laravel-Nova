<?php

namespace App\Http\Controllers\FbReporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\FbReporting\FbPagePost\SubmitPostRequest;
use Illuminate\Http\Request;

class FbPagePostsController extends Controller
{
    public function submit(SubmitPostRequest $request)
    {   
        dd($request->all());
    }
}
