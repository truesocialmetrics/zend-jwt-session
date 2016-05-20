<?php
namespace TweeServerLessSession\Session\Factory;

use TweeServerLessSession\Session\Crypter;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrypterFactory
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        return new Crypter();
    }
}