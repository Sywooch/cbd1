<?php
/**
 * Created by PhpStorm.
 * User: NeiroN
 * Date: 11.10.2015
 * Time: 12:08
 */
namespace app\controllers;
use Yii;
use app\models\Publishing;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class PublishController extends ActiveController
{
    public $modelClass = 'app\models\Publishing';

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
                                'query' => Publishing::find(),
                                'pagination' => false,
                                //'pageSize' => 0,
                            ]);
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
                /*
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
                */
                'options' => [
                    'class' => 'yii\rest\OptionsAction',
                ],
            ];
        }

}
