<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 16.01.17
 * Time: 16:44
 */

$modules = [
    'user' => [
        'class' => 'dektrium\user\Module',
        'enableUnconfirmedLogin' => true,
        'admins' => ['neiron', 'test'],
        'modelMap' => [
            'RegistrationForm' => 'app\models\RegistrationForm',
            'User' => 'app\models\User',
            'LoginForm' => 'app\models\LoginForm',
            'Profile' => 'app\models\Profile'
        ],
        'controllerMap' => [
            'admin' => 'app\controllers\AdminController',
            'registration' => 'app\controllers\RegistrationController',
            'elfinder' => [
                'class' => 'mihaildev\elfinder\Controller',
                'access' => ['@'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
                'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
                'roots' => [
                    [
                        'baseUrl'=>'@web',
                        'basePath'=>'@webroot',
                        'path' => 'uploads/global',
                        'name' => 'Global'
                    ],
                    [
                        'class' => 'mihaildev\elfinder\volume\UserPath',
                        'path'  => 'uploads/user_{id}',
                        'name'  => 'My Documents'
                    ],

                ],
            ]
        ],
    ],
    'rbac' => [
        'class' => 'dektrium\rbac\RbacWebModule',
    ],
    'redactor' => [
        'class' => 'yii\redactor\RedactorModule',
        'uploadDir' => '@webroot/uploadfolder',
        'uploadUrl' => '@web/uploadfolder',
        'imageAllowExtensions'=>['jpg','png','gif']
    ],
    'utility' => [
        'class' => 'c006\utility\migration\Module',
    ],

];

return $modules;