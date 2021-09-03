<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\AuthService;
use App\Services\BitwinService;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var BitwinService
     */
    private BitwinService $bitwinService;
    /**
     * @var OrderService
     */
    private OrderService $orderService;
    /**
     * @var AuthService
     */
    private AuthService $authService;


    public function __construct(BitwinService $bitwinService, OrderService $orderService, AuthService $authService)
    {
        $this->bitwinService = $bitwinService;
        $this->orderService = $orderService;
        $this->authService = $authService;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            // 鏈別 (ERC20 ,TRC20, BEP20 ....)
            $chain = $request->input('chain');
            // 幣別 (BTC, ETH, USDT ....)
            $symbol = $request->input('symbol');
            // 想要儲值的點數數量，非實際儲值金額，需經過轉換及建立 Bitwin 充值單才知道實際要支付多少
            $amount = $request->input('amount');
            // 使用者資訊 - 會員地址
            $address = $request->input('address');
            // 使用者資訊 - 簽名
            $sign = $request->input('sign');
            // 驗證傳過來的使用者地址跟簽名是否跟目前登入的使用者相同
            $user = auth()->user();
            if (!$this->authService->verifySignature($user->nonce, $sign, $address)) {
                throw new Exception('address verification failed');
            }
            // 建立儲值單
            $order = $this->orderService->createOrder($amount, $chain, $symbol);

            return response()->json([
                'message' => 'SUCCESS',
                'order' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function pay(Request $request): JsonResponse
    {
        try {
            // 欲付款訂單編號
            $order_id = $request->input('order_id');
            // 使用者資訊 - 會員地址
            $address = $request->input('address');
            // 使用者資訊 - 簽名
            $sign = $request->input('sign');
            // 驗證傳過來的使用者地址跟簽名是否跟目前登入的使用者相同
            $user = auth()->user();
            if (!$this->authService->verifySignature($user->nonce, $sign, $address)) {
                throw new Exception('address verification failed');
            }
            // 取出未付款的訂單
            $order = Order::where('order_id', $order_id)
                ->where('user_id', $user->id)
                ->where('description', $user->address)  // 之後可能會拿掉，畢竟只是註解
                ->where('status', Order::ORDER_PENDING)
                ->first();
            if (!$order) {
                throw new Exception('order not exists');
            }
            // 建立 BITWIN 付款單
            $result = $this->bitwinService->createCryptoPayOrder([
                'MerchantUserId' => $user->id,
                'MerchantOrderId' => $order->order_id,
                'OrderDescription' => $order->description,
                'Amount' => $order->amount,
                'Symbol' => $order->symbol,
                'CallBackUrl' => '',
                'TimeStamp' => Carbon::now()->timestamp
            ]);


        } catch (Exception $e) {

        }


    }

    public function get(): JsonResponse
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'SUCCESS',
            'orders' => $orders
        ]);
    }
}
