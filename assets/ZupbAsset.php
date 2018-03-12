<?php
/**
 * Created by PhpStorm.
 * User: wolodymyr
 * Date: 18.01.18
 * Time: 12:51
 */

namespace app\assets;


use yii\web\AssetBundle;

class ZupbAsset extends AssetBundle
{
    public $basePath = '@webroot/zupb';
    public $baseUrl = '@web/zupb';
    public $css = [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        'dist/custom.min.css',
        'https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js',
        'https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.js',
        'dist/scripts.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}