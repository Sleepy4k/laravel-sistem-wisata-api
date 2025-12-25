<?php

namespace App\Services\Api\Auth;

use App\Foundations\Service;
use App\Models\PersonalAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionCheckService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'active' => false,
                'valid_until' => null,
                'reason' => 'Missing authentication token',
            ]);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || ($accessToken->expires_at && now()->greaterThan($accessToken->expires_at))) {
            return response()->json([
                'active' => false,
                'valid_until' => null,
                'reason' => 'Invalid or expired authentication token',
            ]);
        }

        $validUntil = $accessToken->expires_at ? $accessToken->expires_at->diffInSeconds(now()) : null;

        if ($validUntil !== null && $validUntil >= 0) {
            return response()->json([
                'active' => false,
                'valid_until' => null,
                'reason' => 'Authentication token has expired',
            ]);
        }

        return response()->json([
            'active' => true,
            'valid_until' => $validUntil ? ceil(-$validUntil) : null,
        ]);
    }
}
