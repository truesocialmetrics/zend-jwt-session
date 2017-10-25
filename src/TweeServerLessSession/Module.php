<?php

namespace TweeServerLessSession;

use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            '*',
            MvcEvent::EVENT_FINISH,
            function ($event) {
                $manager = $event->getApplication()->getServiceManager()->get('Zend\Session\SessionManager');
                if ($manager->sessionExists()) {
                    $manager->writeClose();
                }
            }
        );
    }
}