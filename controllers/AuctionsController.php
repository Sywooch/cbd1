<?php

namespace app\controllers;

use app\models\Bidding;
use app\models\Messages;
use app\models\Eventlog;
use app\components\Mailer;
use app\models\Auctions;
use app\models\AuctionsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DateTime;
use Yii;

/**
 * AuctionsController implements the CRUD actions for Auctions model.
 */
class AuctionsController extends Controller
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

        ];
    }

    public function actionIndex()
    {

        $searchModel = new AuctionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest)
        {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'EnterWatcher ID'));
            return $this->redirect('/user/login');
        }

        $model = $this->findModel($id);

        if($model->user_id==Yii::$app->user->identity->id) {

            Yii::createObject(Eventlog::className())->PutLog([
                'user_id' => Yii::$app->user->identity->id,
                'ip' => Yii::$app->request->getUserIP(),
                'auk_id' => $model->id,
                'action' => Yii::t('app','OrgEnterAuk ID'),
            ]);

            $model->setFlags($id);
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        if(Yii::$app->user->can('admin'))
        {
            Yii::createObject(Eventlog::className())->PutLog([
                'user_id' => Yii::$app->user->identity->id,
                'ip' => Yii::$app->request->getUserIP(),
                'auk_id' => $model->id,
                'action' => Yii::t('app','EnterAuk ID'),
            ]);
            $model->setFlags($id);
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        if(Yii::$app->user->can('watcher'))
        {
            Yii::createObject(Eventlog::className())->PutLog([
                'user_id' => Yii::$app->user->identity->id,
                'ip' => Yii::$app->request->getUserIP(),
                'auk_id' => $model->id,
                'action' => Yii::t('app','WatcherEnterAuk ID'),
            ]);

            $model->setFlags($id);
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        else
        {
            $res = Bidding::find()
                ->select(['status'])
                ->where(['auction_id' => $model->id])
                ->andWhere(['user_id' => Yii::$app->user->id])
                ->limit(1)
                ->one();

            if($res==false)
            {
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'NeedBidding ID'));
                return $this->redirect('/auctions/index');
            }
            elseif($res['status']=="2") // 2=reject
            {
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'BidRejected ID'));
                return $this->redirect('/auctions/index');
            }
            elseif($res['status']=="0") // 0=default
            {
                Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'BidWaiting ID'));
                return $this->redirect('/auctions/index');
            }
            elseif($res['status']=="1") // 0=accept
            {
                Yii::createObject(Eventlog::className())->PutLog([
                    'user_id' => Yii::$app->user->identity->id,
                    'ip' => Yii::$app->request->getUserIP(),
                    'auk_id' => $model->id,
                    'action' => Yii::t('app','EnterAuk ID'),
                ]);
                $model->setFlags($id);
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
            else
            {
                throw new NotFoundHttpException('Page not found');
            }

        }
    }

    /**
     * Creates a new Auctions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('/user/login');
        }
        if(Yii::$app->user->can('admin')) {
            $model = new Auctions();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Auctions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('/user/login');
        }
        if(Yii::$app->user->can('admin')) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Auctions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('/user/login');
        }
        if(Yii::$app->user->can('admin')) {


            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else
        {
            return $this->redirect(['index']);
        }
    }

    public function actionBid($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('/user/login');
        }
        $model = $this->findModel($id);

        $bid_date = new DateTime($model->bidding_date);
        $now_date = new DateTime(date("Y-m-d H:i:s"));
        var_dump($bid_date->diff($now_date));

        if($bid_date < $now_date)
        {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'BiddingTimeEnd ID'));
            return $this->redirect(['/lots/view', 'id' => $model->lot_id]);
        }
        // test for documents
        $sql = Yii::$app->db->createCommand("SELECT id from files WHERE auction_id=:auction_id and user_id=:user_id");
        $sql->bindValues([':auction_id' => $model->id,':user_id' => Yii::$app->user->identity->id]);
        $result = $sql->queryOne();
        if(!$result)
        {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'NeedDownloadFiles ID'));
            return $this->redirect(['/lots/view', 'id' => $model->lot_id]);
        }
        else
        {
            $file_id = $result['id'];
        }
        // test for repeat
        $sql = Yii::$app->db->createCommand("SELECT * from bidding WHERE auction_id=:auction_id and user_id=:user_id");
        $sql->bindValues([':auction_id' => $model->id,':user_id' => Yii::$app->user->identity->id]);
        $result = $sql->queryOne();

        if(!$result)
        {

            Yii::createObject(Bidding::className())->CreateBid(['auction_id' => $model->id, 'user_id' => Yii::$app->user->identity->id, 'org_id'=>$model->user_id,'file_id'=>$file_id]);

            $notes_org = Yii::$app->user->identity->at_org ." ". Yii::t('app', 'NotesOrg ID') .": ". $model->name ." / ".
                Yii::t('app','LotNumber ID'). $model->lot_num ." ". $model->lotName; //Yii::t('app', 'Notes2Org ID');
            $notes_self = Yii::t('app', 'NotesMember ID') .": ". $model->name. " / ". Yii::t('app','Lot ID'). " " .$model->lot_num ." ". $model->lotName;

            Yii::createObject(Messages::className())->CreateMessage(['user_id' => $model->user_id, 'notes' => $notes_org]);

            // отправка на почту (временно)
            $mailer = Yii::$container->get(Mailer::className());
            $mailer->sendMessage("vanouub@meta.ua", $notes_org, 'welcome.php');

            Yii::createObject(Messages::className())->CreateMessage(['user_id' => Yii::$app->user->identity->id, 'notes' => $notes_self]);

            Yii::$app->session->setFlash('success', Yii::t('app', 'BidWellDone ID'));
            return $this->redirect(['index', 'id' => Yii::$app->user->identity->id]);
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'BidExist ID'));
            return $this->redirect(['index', 'id' => Yii::$app->user->identity->id]);
        }
    }

    /**
     * Finds the Auctions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Auctions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auctions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
