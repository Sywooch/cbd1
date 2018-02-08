<?php
/**
 * Created by PhpStorm.
 * User: mxuser
 * Date: 08.02.18
 * Time: 13:55
 */

namespace app\controllers;

use app\models\LoginForm;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\helpers\Url;

class SecurityController extends BaseSecurityController
{

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->goBack(Url::to(['/public']));
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

}