<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\AuthService;
use App\Services\GameService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PlayerController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * @var GameService
     */
    private GameService $gameService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     * @param GameService $gameService
     */
    public function __construct(Authservice $authService, GameService $gameService)
    {
        $this->authService = $authService;
        $this->gameService = $gameService;
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     */
    public function create(): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            // 這邊先限定一個帳號只能有一組遊戲帳號 (現在也只有單幣別
            if (Player::where('user_id', $user->id)->count() > 0) {
                throw new Exception('目前一個地址僅限申請一組遊戲帳號');
            }
            // 雪花演算法建立玩家帳號
            $account = $this->authService->generateAccount(app('Kra8\Snowflake\Snowflake')->next());

            // 呼叫遊戲 API 註冊帳號
            $result = $this->gameService->signup([
                'account' => $account . '_' . Player::PLAYER_SYMBOL_USDT,
                'currency' => Player::PLAYER_SYMBOL_USDT,
                'nickname' => $account
            ]);

            // 建立 USDT 帳號
            $player = new Player;
            $player->user_id = $user->id;
            $player->userUuid = $result['userUuid'];
            $player->symbol = $result['currency'];
            $player->account = $result['account'];
            $player->nickname = $result['nickname'];
            $player->type = $result['accountType'];
            $player->status = Player::PLAYER_STATUS_ENABLED;
            $player->save();

            DB::commit();

            return response()->json([
                'message' => 'SUCCESS',
                'player' => $player
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        try {
            $user = auth()->user();
            $players = Player::where('user_id', $user->id)->get();
            return response()->json([
                'message' => 'SUCCESS',
                'players' => $players
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getBalance(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $account = $request->input('account');
            $player = Player::where('user_id', $user->id)
                ->where('account', $account)
                ->first();
            if (!$player) {
                throw new Exception('遊戲帳號不存在');
            }
            $info = $this->gameService->getUserInfo([
                'account' => $player->account
            ]);
            return response()->json([
                'message' => 'SUCCESS',
                'balance' => $info['balance']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
