<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace app\traits;

use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;

trait AjaxValidationTrait
{

    protected function ajaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $attributes = $model->activeAttributes();
            foreach($attributes as $id => $name){
                if($name == 'captcha'){
                    unset($attributes[$id]);
                }
            }
            \Yii::$app->response->data   = ActiveForm::validate($model);
            \Yii::$app->response->send();
            \Yii::$app->end();
        }
    }
}
