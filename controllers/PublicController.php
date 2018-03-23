<?php

namespace app\controllers;

use api\Items;
use Yii;
use api\Auctions;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\AuctionsSearch;


class PublicController extends Controller
{
    /**
     * Lists all Auctions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuctionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->defaultPageSize = 8;
        $dataProvider->pagination->pageSize = 8;

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auctions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!$model){
            return $this->redirect('/' . $id);
        }
        // if(count($model->awards) == 1){
//             Yii::$app->api->refreshAuction($model->id);
        // }

        Url::remember(['/public/view', 'id' => $id]);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionQuestion(){
        die();
    }

    protected function findModel($id)
    {
        if (($model = Auctions::findOne(['or', ['id' => $id], ['auctionID' => $id]])) !== null) {
            return $model;
        }
        return false;
    }
}