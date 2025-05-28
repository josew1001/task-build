<?php

namespace App\Services;

class ResponseService
{
  
    public function respondCreated(string $message, mixed $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 201);
    }


    public function respondWithData($task = null, $buildings = null, $users = null)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tasks loaded successfully.',
            'tasks' => $task,
            'buildings' => $buildings,
            'users' => $users
        ], 200); 
    }

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