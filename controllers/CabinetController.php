<?php

namespace app\controllers;

use Yii;
use app\models\Cabinet;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;


class CabinetController extends Controller
{

    public $layout = 'user';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $date = date("Y-m-d H:i:s");
        $query = Cabinet::find()
            ->where("bidding_date > '".$date."'")
            ->groupBy('name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
