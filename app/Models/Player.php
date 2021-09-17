<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property int $id
 * @property int $user_id
 * @property string $account
 * @property string $nickname
 * @property string $symbol
 * @property int $status
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUserId($value)
 * @mixin \Eloquent
 * @property string $userUuid
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUserUuid($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereType($value)
 */
class Player extends Model
{
    use HasFactory;

    const PLAYER_STATUS_ENABLED = 1; // 啟用
    const PLAYER_STATUS_DISABLED = 2; // 停用
    const PLAYER_STATUS_BLOCK = 3; // 封鎖

    const PLAYER_TYPE_PROD = 0; // 正式帳號
    const PLAYER_TYPE_TEST = 1; // 測試帳號

    // 開放幣別
    const PLAYER_SYMBOL_USDT = 'USDT';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
