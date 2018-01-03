<?php

require(__DIR__ . '/../vendor/autoload.php');

(new \Dotenv\Dotenv(__DIR__ . '/../'))->load();

defined('YII_DEBUG') or define('YII_DEBUG', getenv('DEBUG') == '1');
defined('YII_ENV') or define('YII_ENV', getenv('ENV'));

require(__DIR__ . '/../functions.php');

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
