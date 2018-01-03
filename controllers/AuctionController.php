<?php
/**
 * Created by PhpStorm.
 * User: NeiroN
 * Date: 11.10.2015
 * Time: 12:08
 */
namespace app\controllers;
use Yii;
use app\models\Auctions;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class AuctionController extends ActiveController
{
    public $modelClass = 'app\models\Auctions';

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
                                    'query' => Auctions::find()->where(['id' => $auc_id]),
                                ]);
                        }
                        else
                        {
                            return new ActiveDataProvider(
                            [
                                'query' => Auctions::find(),
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
                    //'class' => 'yii\rest\UpdateAction',
                    'class' => 'app\controllers\UpdateAction',
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
    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied
    }

}
