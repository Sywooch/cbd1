<?php

namespace app\controllers;

use Yii;
use app\models\Files;
use app\models\Bidding;
use app\models\Messages;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use DateTime;

class BiddingController extends Controller
{

    public $layout = 'user';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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

    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        $model->setAttribute('status','1');
        $model->save(false);

        $notes = Yii::t('app','BiddingConfirm ID').": ".$model->auction->name." / ".
            Yii::t('app','LotNumber ID')." ".
            $model->auction->lot_num ." ". $this->getLotName($model->auction->lot_id);


        Yii::createObject(Messages::className())->CreateMessage(['user_id' => $model->user_id, 'notes' => $notes]);

        return $this->redirect(['index', 'id' => Yii::$app->user->identity->id]);
    }

    public function actionIndex()
    {
        if(Yii::$app->user->can('org') || Yii::$app->user->can('admin'))
        {
            // Obnylator Biddings
            //Yii::$app->db->createCommand("UPDATE bidding SET readed=1 WHERE org_id=:user_id")->bindValue(":user_id",Yii::$app->user->id)->execute();


            $dataProvider = new ActiveDataProvider([
                'query' => Bidding::find()
                    ->leftJoin('auctions', ['bidding.auction_id' => 'auctions.id'])
                    ->leftJoin('lots', ['auctions.lot_id' => 'lots.id'])
                    ->leftJoin('user', ['bidding.user_id' => 'user.id'])
                    ->andFilterWhere(['org_id' => Yii::$app->user->id])
                    ->andFilterWhere(['bidding.org_id' => Yii::$app->user->id]),
                'pagination' => [
                    'pageSize' => 40,
                ]
            ]);
            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);

        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionView()
    {
        Yii::$app->db->createCommand("UPDATE bidding SET readed=1 WHERE user_id=:user_id")->bindValue(":user_id",Yii::$app->user->identity->id)->execute();
        $dataProvider = new ActiveDataProvider([
            'query' => Bidding::find()->where(['user_id' => Yii::$app->user->identity->id,])->orderBy('created_at DESC'),
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->can('member'))
        {
            $bid_date = new DateTime($model->auction->bidding_date);
            $now_date = new DateTime(date("Y-m-d H:i:s"));
            var_dump($bid_date->diff($now_date));
            if($bid_date < $now_date)
            {
                return $this->redirect(['view']);
            }
            if (Yii::$app->request->post())
            {
                $files = new Files();

                $file = UploadedFile::getInstance($files, 'file');
                $file->name = $files->transliteration($file->name);
                if(isset($file))
                {
                    $path = Yii::$app->params['uploadPath'].$file->name;
                    $file->saveAs($path);
                    $files->updateFile([
                            'name'=>$file->name,
                            'file_id'=>$model->file_id]
                    );

                    $notes = Yii::$app->user->identity->at_org." ". Yii::t('app','BiddingEdit ID').": ".
                        $model->auction->name ." / ". Yii::t('app','LotNumber ID')." ".
                        $model->auction->lot_num ." ". $this->getLotName($model->auction->lot_id);
                    $self_notes = Yii::t('app','BiddingEditSelf ID').": ".
                        $model->auction->name." / ".
                        Yii::t('app','LotNumber ID')." ".
                        $model->auction->lot_num ." ". $this->getLotName($model->auction->lot_id);

                    Yii::createObject(Messages::className())->CreateMessage(['user_id' => $model->org_id, 'notes' => $notes]);
                    Yii::createObject(Messages::className())->CreateMessage(['user_id' => Yii::$app->user->identity->id, 'notes' => $self_notes]);

                    return $this->redirect(['view']);
                }
                else
                {
                    print "no file";
                }
            }
            else
            {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->setAttribute('status','2');
        $model->save(false);
        return $this->redirect(['index', 'id' => Yii::$app->user->identity->id]);
    }
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->user->can('member'))
        {
            $bid_date = new DateTime($model->auction->bidding_date);
            $now_date = new DateTime(date("Y-m-d H:i:s"));
            var_dump($bid_date->diff($now_date));
            if ($bid_date < $now_date) {
                return $this->redirect(['view']);
            }
        }
        $notes = Yii::$app->user->identity->at_org." ". Yii::t('app','BiddingDelete ID').": ".
            $model->auction->name ." / ". Yii::t('app','LotNumber ID')." ".
            $model->auction->lot_num ." ". $this->getLotName($model->auction->lot_id);
        $self_notes = Yii::t('app','BiddingDeleteSelf ID').": ".
            $model->auction->name." / ".
            Yii::t('app','LotNumber ID')." ".
            $model->auction->lot_num ." ". $this->getLotName($model->auction->lot_id);;


        Yii::createObject(Messages::className())->CreateMessage(['user_id' => $model->org_id, 'notes' => $notes]);
        Yii::createObject(Messages::className())->CreateMessage(['user_id' => Yii::$app->user->identity->id, 'notes' => $self_notes]);

        $this->findModel($id)->delete();

        if(Yii::$app->user->can('org'))
        {
            return $this->redirect(['index']);
        }
        if(Yii::$app->user->can('member'))
        {
            return $this->redirect(['bidding/view']);
        }
    }
    protected function findModel($id)
    {
        if (($model = Bidding::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function getLotName($id)
    {
        $lotName = Yii::$app->db->createCommand("SELECT name from lots WHERE id=:id")->bindValue(':id',$id);
        $result = $lotName->queryOne();
        return $result['name'];
    }

}
