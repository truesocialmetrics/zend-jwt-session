<?php
namespace TweeServerLessSession\Session\Crypter;

class CleanupDecorator extends AbstractDecorator
{
    public function encrypt($data)
    {
        return $this->getCrypter()->encrypt($data);
    }

    public function decrypt($crypted)
    {
        return $this->getCrypter()->decrypt($crypted);
    }
}