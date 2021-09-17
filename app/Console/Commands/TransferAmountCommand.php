<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransferAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將已經確認收到款項的金額轉換至使用者帳戶';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle()
    {
        $orders = Order::where('status', Order::ORDER_STATUS_COMPLETED)->get();
        foreach ($orders as $order) {
            try {
                DB::beginTransaction();
                $user = User::where('id', $order->user_id)->first();
                switch ($order->symbol) {
                    case 'ETH':
                        $amount = bcadd($user->eth_amount, $order->real_amount, 10);
                        $user->eth_amount = $amount;
                        $user->save();
                        $order->status = Order::ORDER_STATUS_APPRPORTED;
                        $order->save();
                        break;
                    case 'USDT_ERC20':
                        $amount = bcadd($user->usdt_amount, $order->real_amount, 10);
                        $user->usdt_amount = $amount;
                        $user->save();
                        $order->status = Order::ORDER_STATUS_APPRPORTED;
                        $order->save();
                        break;
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        }
    }
}
