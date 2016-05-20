<?php
namespace TweeServerLessSession\Session\SaveHandler\Factory;

use TweeServerLessSession\Session\SaveHandler\StorageLess;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServerLessFactory
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        return new StorageLess(
            $container->get('request'),
            $container->get('response'),
            $container->get('TweeServerLessSessionn\Session\Crypter')
        );
    }
}