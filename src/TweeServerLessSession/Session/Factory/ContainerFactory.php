<?php
namespace TweeServerLessSession\Session\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class ContainerFactory
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        $config = $container->get('config')['di']['instance']['Zend\Session\Container'];
        return new Container($config['parameters']['name'], $container->get('Zend\Session\SessionManager'));
    }
}