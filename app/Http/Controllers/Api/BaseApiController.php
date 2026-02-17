<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseApiController
{
    /**
     * Execute API call with try-catch wrapper
     *
     * @param callable $callback
     * @param string $errorMessage
     * @return JsonResponse
     */
    protected function executeApiWithTryCatch(callable $callback, string $errorMessage = 'حدث خطأ'): JsonResponse
    {
        try {
            return $callback();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->errors(), $e->getMessage(), 422);
        } catch (Exception $e) {
            Log::error('API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // في وضع التطوير، أظهر تفاصيل الخطأ
            $errorDetails = config('app.debug') ? [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : null;

            return $this->error($errorDetails, $errorMessage, 500);
        }
    }

    /**
     * Return success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success($data = null, string $message = 'تمت العملية بنجاح', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return error response
     *
     * @param mixed $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function error($errors = null, string $message = 'حدث خطأ', int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
