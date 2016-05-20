<?php
namespace TweeServerLessSession\Session\Crypter;

use TweeServerLessSession\Session\CrypterInterface;

abstract class AbstractDecorator implements CrypterInterface
{
    protected $crypter = null;

    public function __construct(CrypterInterface $crypter)
    {
        $this->crypter = $crypter;
    }

    public function setCrypter(CrypterInterface $crypter)
    {
        $this->crypter = $crypter;
    }

    public function getCrypter()
    {
        return $this->crypter;
    }
}