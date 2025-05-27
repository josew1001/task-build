<?php

namespace App\Services;

class ResponseService
{
  
    public function respondSuccess($message, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function respondWithError($message, \Exception $exception = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $exception ? $exception->getMessage() : null,
        ], 500);
    }

    public function respondNotFound($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }    
}