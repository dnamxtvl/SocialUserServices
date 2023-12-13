<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

trait RespondWithJsonTrait
{
    protected function respondWithJson(array $content, int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        $response = [
            'data' => $content,
        ];

        return response()->json($response, $status, $headers, $options);
    }
}
