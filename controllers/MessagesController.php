<?php

namespace app\controllers;

use Yii;
use app\models\Messages;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

class MessagesController extends Controller
{

    public $layout = 'user';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
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

    public function actionIndex()
    {
        //$dataProvider = new ActiveDataProvider([
        //    'query' => Messages::find()->where(['user_id' => Yii::$app->user->identity->id]),
        //]);

//        // Obnylator Messages
//        Yii::$app->db->createCommand("UPDATE messages SET status=1 WHERE user_id=:user_id")->bindValue(":user_id",Yii::$app->user->identity->id)->execute();

        // Pagination
        $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM messages WHERE user_id=:user_id', [':user_id' => Yii::$app->user->identity->id])->queryScalar();
        $query = 'SELECT * FROM messages  WHERE user_id=:user_id';

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'params' => [':user_id' => Yii::$app->user->identity->id],
            'totalCount' => (int)$totalCount,
            'sort' => [
                'defaultOrder' => ['status' => SORT_ASC, 'date' => SORT_DESC]
            ],
            'key' => 'id',
            'pagination' => [
                'pageSize' => 30,
            ]
        ]);
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['messages.status' => SORT_ASC],
            'desc' => ['messages.status' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['date'] = [
            'asc' => ['messages.date' => SORT_ASC],
            'desc' => ['messages.date' => SORT_DESC],
        ];
        //$userRole = Yii::$app->authManager->getRole('org');
        //Yii::$app->authManager->assign($userRole, Yii::$app->user->identity->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->status = '1';
        $model->save(false);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->user_id == Yii::$app->user->id){
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Message succesfully removed'));
        }
        return $this->redirect(['index']);
    }

    public function actionMarked(){
        $ids = ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        foreach ($ids as $id){
            $model = $this->findModel($id);
            $model->status = '1';
            $model->save(false);
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Message succesfully marked'));
        return $this->redirect(['index']);
    }

    public function actionRemoveAll(){
        $ids = ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        foreach ($ids as $id){
            $res = $this->actionDelete($id);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
