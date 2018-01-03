<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;


use dektrium\user\controllers\SettingsController as BaseSettingsController;


class SettingsController extends BaseSettingsController
{

    public $layout = 'user';


    public function init(){
        parent::init();
        Yii::$app->session->set('user.flags',Yii::$app->user->identity->id);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['account', 'confirm', 'networks', 'disconnect', 'profile', 'profile-settings'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionProfile()
    {
        $model = $this->finder->findProfileById(Yii::$app->user->identity->getId());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));

            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionProfileSettings()
    {
        $model = $this->finder->findProfileById(Yii::$app->user->identity->getId());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));

            return $this->refresh();
        }

        return $this->render('profile-settings', [
            'model' => $model,
        ]);
    }

}
