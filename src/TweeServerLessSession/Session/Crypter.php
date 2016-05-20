<?php
namespace TweeServerLessSession\Session;

class Crypter implements CrypterInterface
{
    public function encrypt($data)
    {
        return base64_decode($data);
    }

    public function decrypt($crypted)
    {
        return base64_decode($crypted);
    }
}