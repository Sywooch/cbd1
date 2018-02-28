<?php

Yii::setAlias('api', __DIR__ . '/../api/');

$config = [
    'id' => 'etm',
    'name'  =>  'Biddingtime',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['mailer'],
    'charset'   =>  'utf-8',
    'language'  =>  'uk',
    'sourceLanguage' => 'en',
    'timeZone' => 'Europe/Kiev',

    'components' => require(__DIR__ . '/components.php'),
    'modules' => require(__DIR__ . '/modules.php'),
    'params' => require(__DIR__ . '/params.php'),
    'on beforeAction' => function($event) {
        if((Yii::$app->controller->route != 'registration/organizer') && !Yii::$app->user->isGuest &&  !Yii::$app->user->identity->organization){
            return Yii::$app->response->redirect(['/registration/organizer']);
        }
        if(
            Yii::$app->controller->action->id != 'organizer'
            &&
            !Yii::$app->user->isGuest
            &&
            !Yii::$app->user->identity->confirmed_at
        ){
            Yii::$app->response->redirect(['/registration/organizer']);
        }
    }
];


if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['modules']['debug']['allowedIPs'] = ['192.168.2.5','127.0.0.1','10.0.0.1','192.168.2.207', '::1', '93.78.238.18'];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['93.78.238.18','127.0.0.1','10.0.0.1','192.168.2.207', '::1', '93.78.238.18'],
    ];
}

return $config;