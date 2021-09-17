<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Flow
 *
 * @property int $id
 * @property string $flow_id
 * @property string $symbol
 * @property string $amount
 * @property int|null $status
 * @property \datetime $updated_at
 * @property \datetime $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Flow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Flow extends Model
{
    use HasFactory;

    const FLOW_STATUS_PENDING = 0; // 初始狀態
    const FLOW_STATUS_COMPLETED = 1; // 轉入/轉出 成功
    const FLOW_STATUS_FAIL = 2; // 轉入/轉出 失敗

    const FLOW_SYMBOL_USDT = 'USDT';

    const FLOW_TYPE_DEPOSIT = 0; // 轉入
    const FLOW_TYPE_WITHDRAW = 1; // 轉出

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
