<?php
namespace App\Services;

use Elliptic\EC;
use Exception;
use kornrunner\Keccak;

class AuthService
{
    /**
     * @param $pubKey
     * @return string
     * @throws Exception|Exception
     */
    function pubKeyToAddress($pubKey): string
    {
        return "0x" . substr(Keccak::hash(substr(hex2bin($pubKey->encode("hex")), 1), 256), 24);
    }

    /**
     * @param $message
     * @param $signature
     * @param $address
     * @return bool
     * @throws Exception
     */
    function verifySignature($message, $signature, $address): bool
    {
        $msgLen = strlen($message);
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$msgLen}{$message}", 256);
        $sign = ["r" => substr($signature, 2, 64),
            "s" => substr($signature, 66, 64)];
        $reCid = ord(hex2bin(substr($signature, 130, 2))) - 27;
        if ($reCid != ($reCid & 1))
            return false;

        $ec = new EC('secp256k1');
        $pubKey = $ec->recoverPubKey($hash, $sign, $reCid);

        return $address == $this->pubKeyToAddress($pubKey);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return array
     */
    public function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
