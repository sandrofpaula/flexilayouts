<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// Leitura do Arquivo JSON para Seleção de Layout
$layoutConfigPath = __DIR__ . '/../web/data/layout_config.json';
$selectedLayout = 'main'; // Default layout

if (file_exists($layoutConfigPath)) {
    $layoutConfig = json_decode(file_get_contents($layoutConfigPath), true);
    $selectedLayoutId = $layoutConfig['selected'] ?? 1;
    $selectedLayout = array_filter($layoutConfig['layouts'], function($layout) use ($selectedLayoutId) {
        return $layout['id'] == $selectedLayoutId;
    });
    $selectedLayout = reset($selectedLayout);
    $selectedLayout = $selectedLayout['arquivo'] ?? 'main';
}
/////
$config = [
    'id' => 'flexilayouts',
    'name' => 'Flexilayouts',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' => $selectedLayout, // Define o layout selecionado
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'lkswTVq-yFs7-2ZmBShGZivRpkmw7VTd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
