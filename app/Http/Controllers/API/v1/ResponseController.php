<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;

class ResponseController extends Controller
{
    public const MESSAGE = "message";
    public const ERROR = "error";
    public static function response ($status, $message, $status_code){
        return response()->json([
            'status' => $status,
            'data' => $message
        ], $status_code);
    }
}
