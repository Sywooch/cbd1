<?php


(new \Dotenv\Dotenv(__DIR__ . '/../'))->load();

require(__DIR__ . '/../functions.php');


Yii::setAlias('api', __DIR__ . '/../api/');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$apiVersion = getenv('API_VERSION');

return [
    'id' => 'basic-console',
    'name'  =>  'KME',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'user' => 'user.php',
                    ],
                ],
                'rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'rbac' => 'rbac.php',
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_LOGIN'),
                'password' => getenv('SMTP_PASS'),
                'port' => '587',
                'encryption' => 'tls',
            ],
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
        'api' => [
            'class' => '\app\components\Api',
            'url' => getenv('API_URL'),
            'path' => "/api/{$apiVersion}/",
            'apiKey' => getenv('API_KEY'),
        ],
        'apiUpload' => [
            'class' => 'app\components\ApiUpload',
            'url' => getenv('API_DOCUMENTS_URL'),
            'path' => '/',
            'login' => getenv('API_DOCUMENTS_LOGIN'),
            'password' => getenv('API_DOCUMENTS_KEY'),
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => getenv('HOST'),
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'baseUrl' => getenv('HOST'),
        ]
    ],
    'params' => $params,
];
