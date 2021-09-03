<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string|null $chain_id
 * @property string|null $from_address
 * @property string $to_address
 * @property string $tx_hash
 * @property string|null $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $order_id
 * @property string $user_id
 * @property string|null $bitwin_order_id
 * @property string $symbol
 * @property string|null $description
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBitwinOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 */
class Order extends Model
{
    use HasFactory;

    const ORDER_PENDING = 0;
    const ORDER_COMPLETED = 1;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
