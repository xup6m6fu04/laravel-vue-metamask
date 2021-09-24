<?php

namespace App\Services;

use App\Models\BitwinOrder;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Xup6m6fu04\Bitwin;
use Xup6m6fu04\Bitwin\Exception\BitwinSDKException;
use Xup6m6fu04\Bitwin\HTTPClient\GuzzleHTTPClient;

class BitwinService
{
    /**
     * @var GuzzleHTTPClient
     */
    private GuzzleHTTPClient $httpClient;
    /**
     * @var Bitwin
     */
    private Bitwin $bitwin;

    public array $supportChain = ['ERC20', 'TRC20', 'BEP20'];
    public array $supportSymbol = ['BTC', 'ETH', 'USDT'];

    /**
     * @throws Bitwin\Exception\BitwinSDKException
     */
    public function __construct()
    {
        $this->httpClient = new GuzzleHTTPClient([
            'verify' => false
        ]);
        $this->bitwin = new Bitwin($this->httpClient, [
            'merchant_id' => config('bitwin.merchant_id'),
            'sign_key' => config('bitwin.sign_key'),
            'is_prod_environment' => config('bitwin.environment') === 'prod', // true is production environment
        ]);
    }

    /**
     * @throws BitwinSDKException
     */
    public function getETHRate()
    {
        return $this->exchangeRate('ETH');
    }

    /**
     * @throws BitwinSDKException
     */
    public function getBTCRate()
    {
        return $this->exchangeRate('BTC');
    }

    /**
     * @param string $chain
     * @return mixed
     * @throws BitwinSDKException
     */
    public function getUSDTRate(string $chain)
    {
        $chainArray = ['ERC20', 'TRC20', 'BEP20'];
        if (!in_array($chain, $chainArray)) {
            $chain = 'ERC20';
        }
        return $this->exchangeRate('USDT_' . $chain);
    }

    /**
     * @param string $symbol
     * @param string $chain
     * @return string
     */
    public function getSymbol(string $symbol, string $chain): string
    {
        $symbol = strtoupper($symbol);
        $chain = strtoupper($chain);

        if ($symbol !== 'USDT') {
            return $symbol;
        }

        return $symbol . '_' . $chain;
    }

    /**
     * @throws Bitwin\Exception\BitwinSDKException
     */
    private function exchangeRate(string $symbol)
    {
        $args = [
            'Symbol' => $symbol
        ];
        $result = $this->bitwin->api('ExchangeRate')->call($args);
        /**
         * Array
         * (
         *     [RMBRate] => 6.55
         *     [RMBBuyRate] => 6.80
         *     [ReturnCode] => 200
         *     [ReturnMessage] =>
         * )
         */
        if (!$result->isSucceeded()) {
            throw new BitwinSDKException('api call failed');
        }
        $data = $result->getJSONDecodedBody();
        if ($data['ReturnCode'] != 200) {
            throw new BitwinSDKException($data['ReturnMessage']);
        }
        return $data['RMBRate'] > $data['RMBBuyRate'] ? $data['RMBRate'] : $data['RMBBuyRate'];
    }

    /**
     * @throws BitwinSDKException
     */
    public function createCryptoPayOrder(array $args): array
    {
        $result = $this->bitwin->api('CreateCryptoPayOrder')->call($args);
        if (!$result->isSucceeded()) {
            throw new BitwinSDKException('api call failed');
        }
        $data = $result->getJSONDecodedBody();
        if ($data['ReturnCode'] != 200) {
            throw new BitwinSDKException($data['ReturnMessage']);
        }
        return $data;
    }

    public function verifySign($args)
    {
        return $this->verifySign($args);
    }
}
