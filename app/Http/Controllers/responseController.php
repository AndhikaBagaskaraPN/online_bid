<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class responseController extends Controller
{
    public function responseApiWithData($status, $msg, $content, $code){
        return response()->json([
            'status'=>$status,
            'message'=>$msg,
            'data'=>[
                'content'=>$content
            ]
        ], $code);

    }

    public function responseApiWithoutData($status, $msg, $code){
        return response()->json([
            'status'=>$status,
            'message'=>$msg,
        ], $code);
    }
}
