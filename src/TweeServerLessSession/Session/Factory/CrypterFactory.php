<?php
namespace TweeServerLessSession\Session\Factory;

use TweeServerLessSession\Session\Crypter;
use Zend\ServiceManager\ServiceLocatorInterface;
use DomainException;


class CrypterFactory
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        $config = $container->get('config')['di']['instance']['TweeServerLessSession\Session\Factory'];
        if (empty($config['parameters']['key'])) {
            throw new DomainException('Secret key not found for [di][instance][TweeServerLessSession\Session\Factory][parameters][key]');
        }
        return new Crypter\CleanupDecorator(new Crypter($config['parameters']['key']));
    }
}