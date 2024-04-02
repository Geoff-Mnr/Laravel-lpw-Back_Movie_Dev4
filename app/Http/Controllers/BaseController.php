<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function handleResponse($message, $data, $code=200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'meta' => [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem()
            ],
            'links' => [
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage())
            ]
            ], $code);
    }
    public function handleResponseNoPagination($message, $data, $code=200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $code
            ], $code);
    }

    public function handleResponseError($message, $code=404)
    {
        return response()->json([
            'message' => $message,
            'code' => $code
            ], $code);
    }

    public function handleError($message, $code)
    {
        return response()->json([
            'message' => $message,
            'code' => $code
            ], $code);
    }
}
