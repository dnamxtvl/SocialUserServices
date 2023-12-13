<?php

namespace App\Helpers;

use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait RespondWithJsonErrorTrait
{
    protected function respondWithJsonError(
        Throwable $e,
        array $headers = [],
        int $options = 0
    ): JsonResponse
    {
        $code = $e->getCode() ?? Response::HTTP_BAD_REQUEST;
        $message = $e->getMessage() ?? 'An error occurred!';
        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $content = [
            'message' => $message,
            'errors' => [
                'code' => $code,
            ],
        ];

        return response()->json($content, $status, $headers, $options);
    }
}
