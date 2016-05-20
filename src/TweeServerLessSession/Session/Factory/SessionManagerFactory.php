<?php
namespace TweeServerLessSession\Session\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

class SessionManagerFactory
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        $manager      = new SessionManager();
        $manager->setSaveHandler($container->get('TweeServerLessSession\Session\SaveHandler\StorageLess'));

        return $manager;
    }
}