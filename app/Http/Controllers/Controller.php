<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Handle a successful response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'تمت العملية بنجاح', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Handle an error response
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'حدث خطأ أثناء العملية', int $statusCode = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Execute a callback with try-catch and return JSON response
     *
     * @param callable $callback
     * @param string $successMessage
     * @param string $errorMessage
     * @return JsonResponse
     */
    protected function executeWithTryCatch(callable $callback, string $successMessage = 'تمت العملية بنجاح', string $errorMessage = 'حدث خطأ أثناء العملية'): JsonResponse
    {
        try {
            $result = $callback();
            return $this->successResponse($result, $successMessage);
        } catch (Exception $e) {
            Log::error($errorMessage . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse($errorMessage . ': ' . $e->getMessage(), 500);
        }
    }

    /**
     * Execute a callback with try-catch and return redirect response
     *
     * @param callable $callback
     * @param string $successRoute
     * @param string $successMessage
     * @param string $errorMessage
     * @return RedirectResponse
     */
    protected function executeWithTryCatchRedirect(callable $callback, string $successRoute, string $successMessage = 'تمت العملية بنجاح', string $errorMessage = 'حدث خطأ أثناء العملية'): RedirectResponse
    {
        try {
            $callback();
            return redirect()->route($successRoute)->with('success', $successMessage);
        } catch (Exception $e) {
            Log::error($errorMessage . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Execute a callback with try-catch and return view
     *
     * @param callable $callback
     * @param string $view
     * @param string $errorMessage
     * @return mixed
     */
    protected function executeWithTryCatchView(callable $callback, string $view, string $errorMessage = 'حدث خطأ أثناء تحميل الصفحة')
    {
        try {
            $data = $callback();
            return view($view, $data);
        } catch (Exception $e) {
            Log::error($errorMessage . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
