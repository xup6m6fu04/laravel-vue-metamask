<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\User;
use App\Services\AuthService;
use App\Services\GameService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(Authservice $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function generateNonce(Request $request): JsonResponse
    {
        $address = $request->input('address', '');
        if ($address) {
            $nonce = strval(rand(100000000, 2147483647));
            $user = User::firstOrNew(
                ['address' => $address]
            );
            $user->rand = $nonce;
            $user->save();
        }
        return response()->json([
            'message' => 'SUCCESS',
            'nonce' => $nonce ?? ''
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request): JsonResponse
    {
        $signature = $request->input('sign', '');
        $address = $request->input('address', '');
        $user = User::where('address', $address)->first();
        if ($user && $address && $signature) {
            if ($this->authService->verifySignature($user->rand, $signature, $address)) {
                $user->sign = $signature;
                $user->nonce = $user->rand;
                $user->save();
            }
            $token = auth()->login($user);
            return response()->json([
                'message' => 'SUCCESS',
                'auth' => $this->authService->respondWithToken($token)
            ]);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return auth()->check() ? response()->json([
            'message' => 'SUCCESS',
            'address' => auth()->user()->address,
            'sign' => auth()->user()->sign,
            'eth_amount' => auth()->user()->eth_amount,
            'usdt_amount' => auth()->user()->usdt_amount,
        ]) : response()->json([
            'message' => 'FAILURE'
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return array
     */
    public function refresh(): array
    {
        return $this->authService->respondWithToken(auth()->refresh());
    }
}
