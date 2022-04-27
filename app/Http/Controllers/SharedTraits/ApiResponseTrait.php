<?php
namespace app\Http\Controllers\SharedTraits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait {

    public static function sendResponse($message, $result, $code = 200){
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    public static function sendError($error, $code = 404, $errorMessages = []) {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
