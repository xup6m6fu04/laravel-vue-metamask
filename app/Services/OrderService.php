<?php

namespace App\Services;

use App\Models\BitwinOrder;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Xup6m6fu04\Bitwin\Exception\BitwinSDKException;
use function Symfony\Component\String\b;

class OrderService
{
    /**
     * @var BitwinService
     */
    private BitwinService $bitwinService;

    public function __construct(BitwinService $bitwinService)
    {
        $this->bitwinService = $bitwinService;
    }

    /**
     * @throws BitwinSDKException
     * @throws Exception
     */
    public function createOrder(string $amount, string $chain, string $symbol): Order
    {
        // 檢查幣別是否正確
        if (!in_array($symbol, $this->bitwinService->supportSymbol)) {
            throw new Exception('request param error: symbol');
        }

        // 檢查鏈別是否正確
        if ($symbol == 'USDT' && !in_array($chain, $this->bitwinService->supportChain)) {
            throw new Exception('request param error: chain');
        }

        // 雪花演算法建立訂單編號
        $orderId = app('Kra8\Snowflake\Snowflake')->next();
        // 取得使用者
        $user = auth()->user();

        // 新增訂單
        $order = new Order;
        $order->order_id = $orderId;
        $order->user_id = $user->id;
        $order->amount = $amount;
        $order->symbol = $this->bitwinService->getSymbol($symbol, $chain);
        $order->description = $user->address; // 無聊放一下地址在備註
        $order->status = Order::ORDER_STATUS_PENDING;
        $order->expired_at = Carbon::now()->addHour();
        if (!$order->save()) {
            throw new Exception('order save failed');
        }

        return $order;
    }
}
