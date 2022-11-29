<?php

namespace App\Http\Responses\Loan;

use Illuminate\Http\JsonResponse;

class SuccessResponse
{
    /**
     * @param $response
     * @param $message
     * @return JsonResponse
     */
    public static function handleSuccess($response,$message): JsonResponse
    {
       return response()->json([
                   'status'=> true,
                   'message' => $message,
                   'data'=> $response
                ],
           200);
    }

    
}