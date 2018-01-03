<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\Date;
use yii\console\Controller;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParseController extends Controller
{

    public function actionAuctions(){
        return Yii::$app->api->parseAuctions(Date::normalize(date('Y-m-d\TH:i:s', time() - 10800)), false);
    }

    public function actionAll($offset='2017-11-22T00:01:10.096328+02:00'){
        return Yii::$app->api->parseAuctions($offset);
    }

    public function actionRewind(){
        $offset = Date::normalize(date('Y-m-d\TH:i:s', time() + 7000));
        return Yii::$app->api->parseAuctions($offset, false, true);
    }

    public function actionRefresh($id){
        echo Yii::$app->api->refreshAuction($id) ? 'success' : 'unsuccesfull';
    }

}
