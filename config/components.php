<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 16.01.17
 * Time: 16:43
 */
$apiVersion = getenv('API_VERSION');

$components = [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@vendor/dektrium/yii2-user/views' => '@app/views/user',
            ],
        ],
    ],
    'assetManager' => [
        'class' => 'yii\web\AssetManager',
        //'bundles' => [
        //    'yii\web\JqueryAsset' => [
        //        'js' => ['https://code.jquery.com/jquery-3.2.1.min.js'],
            //],
            //'yii\bootstrap\BootstrapAsset' => [
            //    'sourcePath' => null,
            //    'css' => [
            //        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css',
            //    ],
            //    'js' => [
            //        'https://cdn.bootcss.com/popper.js/1.9.3/umd/popper.min.js',
            //        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js',
            //    ]
            //]
        //],
    ],
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => '2Q3pzGXlQIxOPm1TyemO0YlVF1ylwRSm',
        'baseUrl'   =>  '',
        'parsers' => ['application/json' => 'yii\web\JsonParser',], // for rest api
    ],
    'formatter' => [
        'dateFormat' => 'php:d.m.Y',
        'datetimeFormat' => 'php:d.m.Y H:i',
        'timeFormat' => 'php:H:i:s',
        'locale' => 'uk',
        'defaultTimeZone' => 'Europe/Kiev',
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'authManager' => [
        'class' => '\dektrium\rbac\components\DbManager',
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
            'port' => '25',
            //'encryption' => 'tls',
        ],
    ],
    'log' => [
        'traceLevel' => !YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
            [
                'class' => 'sergeymakinen\yii\telegramlog\Target',
                'token' => getenv('LOG_TOKEN'),
                'chatId' => getenv('LOG_CHAT_ID'),
                'levels' => ['error'],
                'template' => "{text}",
                'enabled' => (strlen(getenv('LOG_CHAT_ID') . getenv('LOG_TOKEN')) > 20) && !YII_DEBUG,
            ],
        ],
    ],
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
    'db' => require(__DIR__ . '/db.php'),
    'urlManager' => [
        'showScriptName' => false,
        //'enableStrictParsing' => true, // rest api dont work
        'enablePrettyUrl' => true,
        'rules' => [
            ['class' => 'yii\rest\UrlRule',
                'controller' => ['auction','publish','trade','notification'],
                'pluralize' => false,
                'except' => ['delete']
            ],// for rest api
            '/' => '/site',
            'public/view/<id>' => 'public/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<alias:register>' => 'user/registration/<alias>',
            '<alias:logout|login>' => 'user/security/<alias>',
            '/news/<name:[\w]+>' => '/site/view',
            '/product/<slug:[\w-]+>' => '/products/view',
            '/dopomoga/category/<slug:[\w-]+>' => '/dopomoga/category',
            '/category/<slug:[\w-]+>' => '/categoriesblog/category',
        ]
    ],
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
];

return $components;