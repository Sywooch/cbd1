<?php

namespace app\controllers;

use Yii;
use app\models\Notification;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;


class NotificationController extends ActiveController
{

    public $modelClass = 'app\models\Notification';

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' =>  function ($action)
                {
                    return new ActiveDataProvider(
                        [
                            'query' => Notification::find()->where(['id' => Yii::$app->user->identity->id])->limit('1'),
                        ]);
                }
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                //'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                //'class' => 'yii\rest\UpdateAction',
                'class' => 'app\controllers\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                //'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
    public function checkAccess($action, $model = null, $params = [])
    {
        if(Yii::$app->user->isGuest){
            throw new ForbiddenHttpException();
        }
    }

}
