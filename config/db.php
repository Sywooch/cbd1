<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 18.01.17
 * Time: 12:30
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.getenv('DB_HOST').';dbname=' . getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => 'utf8',
    'enableSchemaCache' => !YII_DEBUG,
];
