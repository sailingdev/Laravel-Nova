<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse($message=null, $data=[], $code=200) {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'errors' => []
        ], $code);
    }

    protected function errorResponse($message=null, $errors=[], $code=500) {
        
        $message = ($message == null && is_string($errors)) ? $errors : $message;
        
        return response()->json([
            'message' => $message,
            'data' => [],
            'errors' => $errors
        ], $code);
    }
}
