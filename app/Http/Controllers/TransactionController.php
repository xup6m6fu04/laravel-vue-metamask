<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $order = new Order;
        $order->chain_id = $request->input('chain_id');
        $order->from_address = $request->input('from_address');
        $order->to_address = $request->input('to_address');
        $order->tx_hash = $request->input('tx_hash');
        $order->amount = $request->input('amount');
        $order->save();
        return response()->json([
           'status' => 200,
           'message' => 'SUCCESS'
        ]);
    }

    public function get(Request $request): JsonResponse
    {
        $from_address = $request->input('from_address', '');
        $chain_id = $request->input('chain_id', '');
        $data = '';
        if ($from_address) {
            $orders = Order::where('from_address', $from_address)
                ->where('chain_id', $chain_id)
                ->orderBy('created_at', 'desc')
                ->get();
            $data = $orders;
        }
        return response()->json([
            'status' => 200,
            'message' => 'SUCCESS',
            'data' => $data
        ]);
    }
}
