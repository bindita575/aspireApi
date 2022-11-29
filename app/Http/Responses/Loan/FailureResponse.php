<?php

namespace App\Http\Responses\Loan;

use Illuminate\Http\JsonResponse;

class FailureResponse
{
    /**
     * @param $errors
     * @return JsonResponse
     */
    public static function handleValidation($errors): JsonResponse
    {
       return response()->json(
               [
                   'status'=> false,
                   'message' => 'Validation Errors',
                   'errors'=>$errors
               ],
           422);
    }

    /**
     * @param string $errorMessage
     * @return JsonResponse
     */
    public static function handleException(string $errorMessage): JsonResponse
    {
        $response = [
            'message' => 'Something went wrong. We have recorded this incident, and please try again later.'.$errorMessage
        ];
        return response()->json($response, 500);
    }
    
}