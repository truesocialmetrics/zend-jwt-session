<?php
namespace TweeServerLessSession\Session;

class Crypter implements CrypterInterface
{
    protected $key = '';

    public function __construct($key)
    {
        $this->setKey($key);
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(16); // AES block size in CBC mode

        // Encryption
        $ciphertext = openssl_encrypt(
            $data,
            'AES-256-CBC',
            mb_substr($this->getKey(), 0, 32, '8bit'),
            OPENSSL_RAW_DATA,
            $iv
        );

        // Authentication
        $hmac = hash_hmac(
            'SHA256',
            $iv . $ciphertext,
            mb_substr($this->getKey(), 32, null, '8bit'),
            true
        );
        return $hmac . $iv . $ciphertext;

        return base64_encode($data);
    }

    public function decrypt($data)
    {
        $hmac       = mb_substr($data, 0, 32, '8bit');
        $iv         = mb_substr($data, 32, 16, '8bit');
        $ciphertext = mb_substr($data, 48, null, '8bit');

        // Authentication
        $hmacNew = hash_hmac(
            'SHA256',
            $iv . $ciphertext,
            mb_substr($this->getKey(), 32, null, '8bit'),
            true
        );
        if (! $this->hashEquals($hmac, $hmacNew)) {
            throw new \RuntimeException('Authentication failed');
        }

        // Decrypt
        return openssl_decrypt(
            $ciphertext,
            'AES-256-CBC',
            mb_substr($this->getKey(), 0, 32, '8bit'),
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_decode($crypted);
    }

    public function hashEquals($expected, $actual)
    {
        $expected     = (string) $expected;
        $actual       = (string) $actual;
        if (function_exists('hash_equals')) {
            return hash_equals($expected, $actual);
        }
        $lenExpected  = mb_strlen($expected, '8bit');
        $lenActual    = mb_strlen($actual, '8bit');
        $len          = min($lenExpected, $lenActual);
        $result = 0;
        for ($i = 0; $i < $len; $i++) {
            $result |= ord($expected[$i]) ^ ord($actual[$i]);
        }
        $result |= $lenExpected ^ $lenActual;
        return ($result === 0);
    }
}