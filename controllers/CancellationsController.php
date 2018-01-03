<?php

namespace app\controllers;

use api\Auctions;
use Yii;
use api\Cancellations;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Messages;
use yii\helpers\Html;
use api\Documents;


class CancellationsController extends Controller
{

    public $layout = 'user';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function(){
                            return Yii::$app->user->can('org');
                        }
                    ]
                ]
            ],
        ];
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('admin') && (!Yii::$app->user->can('org') || $mdoel->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionConfirm($id){
        if(false == ($auction = Auctions::findOne(['unique_id' => $id])) || (false == ($cancellation = $auction->cancellation))){
            throw new NotFoundHttpException();
        }
        if($cancellation->confirm()){

            $text = Yii::t('app', 'Аукціон {link} скасовано.', [
                    'link' => Html::a($auction->title, Url::to(['/public/view', 'id' => $auction->unique_id], true)),
            ]);

            if($auction->lot && $auction->lot->bids){
                foreach($auction->lot->bids as $bid){
                    if($bid->user_id != '0'){
                        $text = Yii::t('app', 'Аукціон {link} скасовано.', [
                                'link' => Html::a($auction->title, Url::to(['/public/view', 'id' => $auction->unique_id], true)),
                            ]);
                        Yii::createObject(Messages::className())->sendMessage($bid->user_id, $text, true);
                    }
                }
            }
            foreach($auction->questions as $question){
                if($question->author && $question->author->user_id != 0){
                    Yii::createObject()->sendMessage($question->author->user_id, $text, true);
                }
            }
            $text = Yii::t('app', 'Auction successfully cancelled. {link}', [
                'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/lots/view', 'id' => $auction->baseAuction_id], true)),
            ]);
            Yii::createObject(Messages::className())->sendMessage(Yii::$app->user->id, $text, true);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Cancellation succesfully confirmed'));
            return $this->redirect(['/lots/view', 'id' => $auction->baseAuction_id]);
        }
        else{
            Yii::$app->session->setFlash('success', Yii::t('app', 'Something went wrong'));
            return $this->goBack();
        }
    }

    public function actionUploadDocument($id){
        if(false == ($auction = Auctions::findOne(['unique_id' => $id])) || (false == ($model = $auction->cancellation))){
            throw new NotFoundHttpException();
        }
        $model->load(Yii::$app->request->post());
        if($model->uploadDocument()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Document uploaded successfully'));
            return $this->redirect(['/lots/view', 'id' => $auction->baseAuction_id]);
        }
        return $this->render('upload-document', ['model' => $model]);
    }

    public function actionReupload($id, $document_id){
        if(false == ($auction = Auctions::findOne(['unique_id' => $id]))
            || (false == ($document = Documents::findOne(['unique_id' => $document_id])))
            || (false == ($model = $auction->cancellation))){
            throw new NotFoundHttpException();
        }
        $model->load(Yii::$app->request->post());
        if($model->reuploadDocument($document)){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Document reuploaded successfully'));
            return $this->redirect(['/lots/view', 'id' => $auction->baseAuction_id]);
        }
        return $this->render('upload-document', ['model' => $model, 'document' => $document]);
    }

    public function actionCreate($id)
    {
        if(false == ($auction = Auctions::findOne(['unique_id' => $id]))){
            throw new NotFoundHttpException();
        }
        $lot = $auction->lot;
        if(!empty($auction->contracts)){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot cancel started auction'));
            return $this->redirect(Url::previous());
        }
        $model = new Cancellations();
        $model->cancellationOf = 'auction';
        $model->relatedItem = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $data = Yii::$app->api->createCancellation($model);
            $model->load($data['data'], '');
            $model->save(false);

            return $this->redirect(Url::previous());
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Cancellations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cancellations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cancellations::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}