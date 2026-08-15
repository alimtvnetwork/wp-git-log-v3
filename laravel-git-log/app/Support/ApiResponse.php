<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Create a universal response envelope.
     */
    public static function make(
        bool $isSuccess,
        int $code,
        string $message,
        array $results = [],
        array $attributes = []
    ): JsonResponse {
        return new JsonResponse([
            'Status' => [
                'IsSuccess' => $isSuccess,
                'Code' => $code,
                'Message' => $message,
            ],
            'Attributes' => array_merge([
                'RequestedAt' => now()->toIso8601String(),
            ], $attributes),
            'Results' => $results,
        ], $code);
    }

    /**
     * Return a successful response.
     */
    public static function success(
        array $results = [],
        string $message = 'OK',
        int $code = 200,
        array $attributes = []
    ): JsonResponse {
        return self::make(true, $code, $message, $results, $attributes);
    }

    /**
     * Return a failure response containing ErrorEnvelope(s).
     */
    public static function fail(
        ErrorEnvelope $error,
        int $code = 400,
        array $attributes = []
    ): JsonResponse {
        return self::make(false, $code, $error->message, [$error->toArray()], $attributes);
    }
}
