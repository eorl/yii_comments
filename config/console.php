<?php

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'websocket' => [
            'class' => 'morozovsk\yii2websocket\Connection',
            'servers' => [
                'posts' => [
                    'class' => 'app\daemons\WebSocketDaemonHandler',
                    'pid' => '/tmp/websocket_chat.pid',
                    'websocket' => 'tcp://127.0.0.1:8004',
                    'localsocket' => 'tcp://127.0.0.1:8010',
                ]
            ],
        ],
    ],
    'controllerMap' => [
        'websocket' => 'morozovsk\yii2websocket\console\controllers\WebsocketController'
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
