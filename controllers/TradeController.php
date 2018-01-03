<?php

namespace app\controllers;

use app\models\Trade;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\Request;

class TradeController extends ActiveController
{
    public $modelClass = 'app\models\Trade';

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' =>  function ($action)
                {
                    //$auc_id = Yii::$app->session->get('user.flags'); // NADA PREPISAT!!!
                    if(Yii::$app->request->get()) { $auc_id = $_GET['id']; }
                    if(isset($auc_id))
                    {
                        return new ActiveDataProvider(
                            [
                                'query' => Trade::find()->where(['auk_id' => $auc_id])->orderBy('id DESC'),
                                'pagination' => [
                                    'defaultPageSize' => 5,
                                ],
                            ]);
                    }
                    else
                    {
                        return new ActiveDataProvider(
                            [
                                'query' => Trade::find(),
                            ]);
                    }
                }
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
}
