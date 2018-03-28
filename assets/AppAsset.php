<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'css/font-awesome.css',
        'css/popover.css',
        'dist/custom.min.css',
        'css/fixes.css'
    ];
    public $js = [
        'js/sweetalert.js',
        'js/popper.js',
        'js/bootstrap.js',
        'js/popover.js',
        'js/accounting.js',
        'dist/scripts.min.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init(){
        if(!YII_DEBUG){
            $this->js[] = 'js/accounting.js';
        }
        parent::init();
    }
}
