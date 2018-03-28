<?php

namespace app\controllers;

use api\Items;
use Yii;
use api\Auctions;
use api\Questions;
use app\models\Lots;
use yii\helpers\Url;
use app\helpers\Date;
use yii\web\Controller;
use app\models\Messages;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\Html;

/**
 * QuestionsController implements the CRUD actions for Questions model.
 */
class QuestionsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'answer'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single Questions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id, $item_id = null)
    {
        $auction = Auctions::findOne($id);
        if(!$auction){
            throw new NotFoundHttpException();
        }

        if($item_id){
            $item = Items::findOne(['id' => $item_id]);
            if(!$item){
                throw new NotFoundHttpException();
            }
        }

        if($auction->baseAuction && $auction->baseAuction->user_id == Yii::$app->user->id){
            throw new ForbiddenHttpException();
        }
        if((strtotime($auction->enquiryPeriod_endDate)) <= time()){
            Yii::$app->session->setFlash('info', Yii::t('app', 'Can add question only in enquiryPeriod'));
            return $this->redirect(Url::previous());
        }
        if(false == ($lot = Lots::findOne(['id' => $auction->baseAuction_id]))){
            $lot = new Lots;
            $lot->load($auction->attributes, '');
            $lot->lot_lock = '3';
            $lot->date = Date::normalize(date('Y-m-d H:i:s', time()));
            $lot->load($auction->attributes, '');
            $lot->save(false);
            $auction->updateAttributes(['baseAuction_id' => $lot->id]);
        }

        $model = new Questions();
        $model->setScenario('create');

        if($item_id){
            $model->relatedItem = $item_id;
            $model->questionOf = 'item';
        }
        else
        {
            $model->relatedItem = $auction->id;
            $model->questionOf = 'tender';
        }

        $model->author_id = Yii::$app->user->identity->organization->unique_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->api->createQuestion($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Question created successfully'));

            Yii::createObject(Messages::className())
                ->sendMessage(
                    $lot->user_id,
                    Yii::t('app', 'You accepted new question about auction')
                    . '. '.
                    Html::a(
                        Yii::t('app', 'Answer the question'),
                        Url::to([
                            '/questions/answer',
                            'id' => $model->unique_id,
                        ], true)), true);
            return $this->redirect(['/public/view', 'id' => $auction->auctionID]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionAnswer($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('org') || ($model->auction && $model->auction->lot && $model->auction->lot->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }
        if($model->answer){
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Question already answered'));
            return $this->goBack();
        }

        $model->setScenario('answer');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateAttributes(['dateAnswered' => Date::normalize(date('Y-m-d H:i:s', time()))]);
            Yii::$app->api->answerQuestion($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Answer created'));
            if($model->organization){
                Yii::createObject(Messages::className())
                    ->sendMessage(
                        $model->organization->user->id,
                        Yii::t('app', 'Your question have answer') . '. '.
                        Html::a(
                            Yii::t('app', 'View your question'),
                            Url::to([
                                '/questions/view',
                                'id' => $model->unique_id,
                            ], true))
                        , true);
            }

            return $this->redirect(['/public/view', 'id' => $model->auction->auction->id]);
        } else {
            return $this->render('answer', [
                'model' => $model,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Questions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
