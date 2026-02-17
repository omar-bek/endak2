<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractToken($request);

        if (!$token) {
            \Illuminate\Support\Facades\Log::warning('API Token missing', [
                'path' => $request->path(),
                'method' => $request->method()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Missing API token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $hashedToken = hash('sha256', $token);
        $user = User::where('api_token', $hashedToken)->first();

        if (!$user) {
            \Illuminate\Support\Facades\Log::warning('API Token invalid', [
                'path' => $request->path(),
                'method' => $request->method(),
                'token_preview' => substr($token, 0, 10) . '...'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired API token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        \Illuminate\Support\Facades\Log::info('API Token authenticated', [
            'user_id' => $user->id,
            'path' => $request->path()
        ]);

        auth()->setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        $authorization = $request->header('Authorization');

        if ($authorization && str_starts_with($authorization, 'Bearer ')) {
            return substr($authorization, 7);
        }

        return $request->query('api_token') ?? $request->input('api_token');
    }
}









