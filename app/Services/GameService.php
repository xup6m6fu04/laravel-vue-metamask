<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;

class GameService
{
    private string $token;
    private Client $client;
    private string $serverUrl;
    private string $merchantUrl;

    public function __construct()
    {
        $this->token = config('game.token');
        $this->merchantUrl = config('game.merchant.url');
        $this->serverUrl = config('game.server.url');
        $this->client = new Client(
            [
                'timeout' => 20,
                'connect_timeout' => 20,
                'headers' => ['token' => $this->token],
                'verify' => false
            ]
        );
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handleApi($url, $args)
    {
        $response = $this->client->post($url, ['form_params' => $args]);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('api failure: ' . $response->getStatusCode());
        }
        $body = json_decode($response->getBody(), true);
        if ($body['result'] !== 'ok') {
            throw new Exception('api return error message: ' . $body['error']);
        }
        return $body['payload'];
    }

    /**
     * @throws Exception
     */
    public function handleValidate($validator)
    {
        if ($validator->stopOnFirstFailure()->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    /**
     * 玩家註冊
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "userUuid": "00c65b556b645300",
     *     "account": "y01",
     *     "nickname": "",
     *     "balance": "",
     *     "currency": "USDT",
     *     "merchant": "yozero",
     *     "host": "",
     *     "label": "",
     *     "accountType": "1",
     *     "createdAt": "2021-09-08T06:33:10Z"
     *   }
     * }
     */
    public function signup(array $args)
    {
        $url = $this->merchantUrl . '/signup';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'currency' => 'required|string|in:USDT',
            'host' => 'string|max:50',
            'label' => 'string|max:50',
            'accountType' => 'string|in:0,1',
            'nickname' => 'string|max:20'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 玩家登入
     *
     * @throws Exception
     * @throws GuzzleException
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "andarbahar": "https://stage-andarbahar.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "baccarat2": "https://stage-baccarat.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "baccaratlive": "https://stage-bobolove-lobby.bb-bcgames.com/?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "dragontiger": "https://stage-longhu.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "lobby": "https://stage-lobby.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true",
     *     "pk10": "https://stage-mpk10.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "sedie": "https://stage-sedie.bb-bcgames.com/?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "teenpatti": "https://stage-teenpatti.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true",
     *     "yuxiaxie": "https://stage-yuxiaxie.bb-bcgames.com?token=00c6b21360d332d05Vhf33jTbrFX2aXt&type=merchant&exit_option=4&account=y01&currency=USDT&direct=true&home=true"
     *   }
     * }
     */
    public function login(array $args)
    {
        $url = $this->merchantUrl . '/login';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'exit_option' => 'string|in:1,2,3,4',
            'param' => 'string',
            'lang' => 'string|in:zh-CN,zh-US',
            'closed' => 'string|in:true,false'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 玩家登出
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": "ok"
     * }
     */
    public function logout($args)
    {
        $url = $this->merchantUrl . '/logout';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 取得玩家資料
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "userUuid": "00c65b556b645300",
     *     "account": "y01",
     *     "nickname": "",
     *     "balance": "0.000000",
     *     "currency": "USDT",
     *     "merchant": "yozero",
     *     "host": "",
     *     "label": "",
     *     "accountType": "1",
     *     "createdAt": "2021-09-08T06:33:10Z"
     *   }
     * }
     */
    public function getUserInfo($args)
    {
        $url = $this->merchantUrl . '/getUserInfo';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 存款
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "newBalance": "100000.000000",
     *     "oldBalance": "0.000000",
     *     "userInfo": {
     *       "userUuid": "00c65b556b645300",
     *       "account": "y01",
     *       "nickname": "",
     *       "balance": "100000.000000",
     *       "currency": "USDT",
     *       "merchant": "yozero",
     *       "host": "",
     *       "label": "",
     *       "accountType": "1",
     *       "createdAt": "2021-09-08T06:33:10Z"
     *     }
     *   }
     * }
     */
    public function deposit($args)
    {
        $url = $this->merchantUrl . '/deposit';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'orderCode' => 'required|string|max:100',
            'amount' => 'required|string'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 取款
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *     "payload": {
     *     "newBalance": "99000.000000",
     *     "oldBalance": "100000.000000",
     *     "userInfo": {
     *       "userUuid": "00c65b556b645300",
     *       "account": "y01",
     *       "nickname": "",
     *       "balance": "99000.000000",
     *       "currency": "USDT",
     *       "merchant": "yozero",
     *       "host": "",
     *       "label": "",
     *       "accountType": "1",
     *       "createdAt": "2021-09-08T06:33:10Z"
     *     }
     *   }
     * }
     */
    public function withdraw($args)
    {
        $url = $this->merchantUrl . '/withdraw';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'orderCode' => 'required|string|max:100',
            'amount' => 'required|string'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 取款(所有餘額)
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "newBalance": "0",
     *     "oldBalance": "99000.000000",
     *     "userInfo": {
     *       "userUuid": "00c65b556b645300",
     *       "account": "y01",
     *       "nickname": "",
     *       "balance": "0",
     *       "currency": "USDT",
     *       "merchant": "yozero",
     *       "host": "",
     *       "label": "",
     *       "accountType": "1",
     *       "createdAt": "2021-09-08T06:33:10Z"
     *     }
     *   }
     * }
     */
    public function withdrawAll($args)
    {
        $url = $this->merchantUrl . '/withdrawAll';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'orderCode' => 'required|string|max:100',
            'accountType' => 'string|in:0,1'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * 存/取款歷程查詢
     *
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "amountInfoList": [
     *       {
     *         "userInfo": {
     *           "userUuid": "00c65b556b645300",
     *           "account": "y01",
     *           "nickname": "",
     *           "balance": "0.000000",
     *           "currency": "USDT",
     *           "merchant": "yozero",
     *           "host": "",
     *           "label": "",
     *           "accountType": "1",
     *           "createdAt": "2021-09-08T06:33:10Z"
     *         },
     *         "amountHistory": {
     *           "orderUuid": "00c6b3a0e1d332d0",
     *           "userUuid": "00c65b556b645300",
     *           "orderCode": "003",
     *           "memo": "withdraw",
     *           "amount": "-99000.000000",
     *           "newBalance": "0.000000",
     *           "oldBalance": "99000.000000",
     *           "createdAt": "2021-09-09T08:16:14Z"
     *         }
     *       },
     *       {
     *         "userInfo": {
     *           "userUuid": "00c65b556b645300",
     *           "account": "y01",
     *           "nickname": "",
     *           "balance": "0.000000",
     *           "currency": "USDT",
     *           "merchant": "yozero",
     *           "host": "",
     *           "label": "",
     *           "accountType": "1",
     *           "createdAt": "2021-09-08T06:33:10Z"
     *         },
     *         "amountHistory": {
     *           "orderUuid": "00c6b382e63332d0",
     *           "userUuid": "00c65b556b645300",
     *           "orderCode": "002",
     *           "memo": "withdraw",
     *           "amount": "-1000.000000",
     *           "newBalance": "99000.000000",
     *           "oldBalance": "100000.000000",
     *           "createdAt": "2021-09-09T08:14:11Z"
     *         }
     *       },
     *       {
     *         "userInfo": {
     *           "userUuid": "00c65b556b645300",
     *           "account": "y01",
     *           "nickname": "",
     *           "balance": "0.000000",
     *           "currency": "USDT",
     *           "merchant": "yozero",
     *           "host": "",
     *           "label": "",
     *           "accountType": "1",
     *           "createdAt": "2021-09-08T06:33:10Z"
     *         },
     *         "amountHistory": {
     *           "orderUuid": "00c6b35bf70332d0",
     *           "userUuid": "00c65b556b645300",
     *           "orderCode": "001",
     *           "memo": "deposit",
     *           "amount": "100000.000000",
     *           "newBalance": "100000.000000",
     *           "oldBalance": "0.000000",
     *           "createdAt": "2021-09-09T08:11:32Z"
     *         }
     *       }
     *     ],
     *     "totalCount": "3"
     *   }
     * }
     */
    public function transPeriod($args)
    {
        $url = $this->merchantUrl . '/transPeriod';
        $validator = validator($args, [
            'startTime' => 'string|max:20',
            'endTime' => 'string|max:20',
            'account' => 'string|max:100',
            'accountType' => 'string|in:0,1',
            'orderCode' => 'string|max:100',
            'page' => 'numeric',
            'pageSize' => 'numeric'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "sedie": [
     *         {
     *           "wagerId": "1",
     *           "brief": "4白"
     *         },
     *         {
     *           "wagerId": "2",
     *           "brief": "4红"
     *         },
     *         {
     *           "wagerId": "3",
     *           "brief": "3白1红"
     *         },
     *         {
     *           "wagerId": "4",
     *           "brief": "3红1白"
     *         },
     *         {
     *           "wagerId": "5",
     *           "brief": "单"
     *         },
     *         {
     *           "wagerId": "6",
     *           "brief": "双"
     *         }
     *       ]
     *     }
     *   }
     */
    public function getDescription($args)
    {
        $url = $this->merchantUrl . '/gameApi/gameDescription';
        $validator = validator($args, [
            'gameType' => 'string',
            'lang' => 'string|in:en-US,zh-TW'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getRoomList($args)
    {
        $url = $this->merchantUrl . '/gameApi/roomList';
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "List": {
     *       "andarbahar": {
     *         "0072abb7fe547f30": {
     *           "account": "mindy",
     *           "amount": "260",
     *           "channel": "",
     *           "currency": "CNY",
     *           "merchant": "zeus",
     *           "orderCount": "5",
     *           "prize": "-30",
     *           "roundCount": "3",
     *           "totalBet": "260",
     *           "userUuid": "0072abb7fe547f30"
     *         },
     *         "0123456789abcdef": {
     *           "account": "heartzTest",
     *           "amount": "200",
     *           "channel": "",
     *           "currency": "CNY",
     *           "merchant": "heartz",
     *           "orderCount": "2",
     *           "prize": "180",
     *           "roundCount": "2",
     *           "totalBet": "200",
     *           "userUuid": "0123456789abcdef"
     *         }
     *       }
     *     },
     *     "TotalCount": "6"
     *   }
     * }
     */
    public function getPlayerTotalBet($args)
    {
        $url = $this->merchantUrl . '/gameApi/playerTotalBet';
        $validator = validator($args, [
            'startTime' => 'string|max:20',
            'endTime' => 'string|max:20',
            'account' => 'string|max:100',
            'accountType' => 'string|in:0,1',
            'userUuid' => 'string|max:100',
            'page' => 'numeric',
            'pageSize' => 'numeric',
            'currency' => 'required|string|in:USDT',
            'gameType' => 'string',
            'roomRound' => 'string',
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "totalBet": {
     *       "currency": "CNY",
     *       "amount": "1211005",
     *       "totalBet": "1166305",
     *       "prize": "78225.7",
     *       "orderCount": "669"
     *     },
     *   "accountCount": "57"
     *   }
     * }
     */
    public function getTotalBet($args)
    {
        $url = $this->merchantUrl . '/gameApi/totalBet';
        $validator = validator($args, [
            'startTime' => 'required|string|max:20',
            'endTime' => 'required|string|max:20',
            'accountType' => 'string|in:0,1',
            'currency' => 'required|string|in:USDT',
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result":"ok",
     *   "error":"",
     *   "payload":{
     *     "List":{
     *       "baccarat2":[
     *         {
     *           "account":"emmaCNY1",
     *           "amount":[
     *             "50000"
     *           ],
     *           "bankerCard":[
     *             {
     *               "cardCode":"",
     *               "cardHash":"",
     *               "cardIndex":"030",
     *               "cardValue":{
     *                 "cardFaceValue":13,
     *                 "cardSuit":"s"
     *               }
     *             },
     *             {
     *               "cardCode":"",
     *               "cardHash":"",
     *               "cardIndex":"032",
     *               "cardValue":{
     *                 "cardFaceValue":6,
     *                 "cardSuit":"s"
     *               }
     *             }
     *           ],
     *           "bankerPoint":"6",
     *           "batch":"",
     *           "betType":"0",
     *           "betUuid":"56020387553873824",
     *           "channel":"",
     *           "choose":[
     *             "1"
     *           ],
     *           "createdAt":"2021-09-10T08:20:04Z",
     *           "currency":"CNY",
     *           "gameType":"5",
     *           "merchant":"zeus",
     *           "playerCard":[
     *             {
     *               "cardCode":"",
     *               "cardHash":"",
     *               "cardIndex":"029",
     *               "cardValue":{
     *                 "cardFaceValue":12,
     *                 "cardSuit":"s"
     *               }
     *             },
     *             {
     *               "cardCode":"",
     *               "cardHash":"",
     *               "cardIndex":"031",
     *               "cardValue":{
     *                 "cardFaceValue":1,
     *                 "cardSuit":"h"
     *               }
     *             },
     *             {
     *               "cardCode":"",
     *               "cardHash":"",
     *               "cardIndex":"033",
     *               "cardValue":{
     *                 "cardFaceValue":9,
     *                 "cardSuit":"d"
     *               }
     *             }
     *           ],
     *           "playerPoint":"0",
     *           "prize":[
     *             "47500"
     *           ],
     *           "roomIndex":"1",
     *           "roomRound":"Baccarat-T1-966-7",
     *           "round":"",
     *           "totalBet":[
     *             "50000"
     *           ],
     *           "updatedAt":"2021-09-10T08:20:15Z",
     *           "userUuid":"00c6a0539ab332d0",
     *           "winLoseUuid":"00c706414e840fa0"
     *         }
     *       ]
     *     },
     *     "TotalCount":"1"
     *   }
     * }
     *
     */
    public function getTransaction($args)
    {
        $url = $this->merchantUrl . '/gameApi/transaction';
        $validator = validator($args, [
            'startTime' => 'required|string|max:20',
            'endTime' => 'required|string|max:20',
            'page' => 'numeric',
            'pageSize' => 'numeric',
            'account' => 'string|max:100',
            'accountType' => 'string|in:0,1',
            'userUuid' => 'string|max:100',
            'betUuid' => 'string|max:100',
            'currency' => 'required|string|in:USDT',
            'gameType' => 'string',
            'roomRound' => 'string',
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     * Api Success Example
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *   "  Data": "https://stage-game-detail.bb-bcgames.com/?timeZone=%2B8&orderUuid=28384512388080115&account=johnZeusInr&gametype=baccarat2&"
     *   }
     * }
     */
    public function getTransactionDetail($args)
    {
        $url = $this->merchantUrl . '/gameApi/transactionDetail';
        $validator = validator($args, [
            'account' => 'required|string|max:100',
            'betUuid' => 'required|string|max:100',
            'gameType' => 'required|string',
            'timeZone' => 'string'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     *
     *
     * {
     *   "result": "ok",
     *   "error": "",
     *   "payload": {
     *     "memberList": [
     *       {
     *         "userUuid": "006805818fc354e0",
     *         "account": "kenTest",
     *         "nickname": "",
     *         "balance": "100000050.500000",
     *         "currency": "CNY",
     *         "merchant": "kenkenken",
     *         "host": "rdTeam",
     *         "label": "backend",
     *         "accountType": "0",
     *         "createdAt": "2020-11-19T04:24:20Z"
     *       },
     *       {
     *         "userUuid": "00680ec9af850b20",
     *         "account": "player001",
     *         "nickname": "",
     *         "balance": "0.000000",
     *         "currency": "CNY",
     *         "merchant": "kenkenken",
     *         "host": "",
     *         "label": "",
     *         "accountType": "0",
     *         "createdAt": "2020-11-19T07:06:33Z"
     *       }
     *     ],
     *     "totalCount": "230"
     *   }
     * }
     */
    public function getMemberList($args)
    {
        $url = $this->merchantUrl . '/gameApi/memberList';
        $validator = validator($args, [
            'page' => 'numeric',
            'pageSize' => 'numeric'
        ]);
        $this->handleValidate($validator);
        return $this->handleApi($url, $args);
    }
}