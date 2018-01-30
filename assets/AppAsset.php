<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public function init(){
        Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = [

        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => null,
            'css' => [
                'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css',
            ],
            'js' => [
                'https://cdn.bootcss.com/popper.js/1.9.3/umd/popper.min.js',
                'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js',
            ]
        ];
        parent::init();
    }

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        'https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.css',
        'dist/custom.min.css',
        'css/fixes.css'
    ];
    public $js = [
        //'https://code.jquery.com/jquery-3.2.1.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.all.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js',
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js',
        'https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.js',
        'js/accounting.js',
        'dist/scripts.min.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
