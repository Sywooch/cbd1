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

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if($model) {
            Yii::$app->session->remove('redirected');
        }else{
            return $this->softRedirect($id);
        }
        Url::remember(['/public/view', 'id' => $id]);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Auctions::find()->where(['or', ['id' => $id], ['auctionID' => $id]])->one()) !== null) {
            return $model;
        }
        return false;
    }

    public function softRedirect($id){
        if(Yii::$app->session->has('redirected')){
            Yii::$app->session->remove('redirected');
            throw new NotFoundHttpException();
        }
        Yii::$app->session->set('redirected', true);
        return $this->redirect(getenv('BRO_URL') . '/public/view/' . $id);
    }
}
