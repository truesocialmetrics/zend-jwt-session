<?php

return [
    'di' => [
        'instance' => [
            'Zend\Session\Container' => [
                'parameters' => [
                    'name' => 'Default',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'TweeServerLessSession\Session\Crypter'                => 'TweeServerLessSession\Session\Factory\CrypterFactory',
            'TweeServerLessSession\Session\SaveHandler\ServerLess' => 'TweeServerLessSession\Session\SaveHandler\Factory\ServerLessFactory',
            'Zend\Session\SessionManager'                          => 'TweeServerLessSession\Session\Factory\SessionManagerFactory',
            'Zend\Session\Container'                               => 'TweeServerLessSession\Session\Factory\ContainerFactory',
        ],
    ],
];