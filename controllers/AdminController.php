<?php
/**
 * Created by PhpStorm.
 * User: wolodymyr
 * Date: 16.02.17
 * Time: 9:56
 */

namespace app\controllers;



use Yii;
use app\models\EmailTasks;
use app\models\User;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class AdminController extends \dektrium\user\controllers\AdminController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login-user' => ['POST'],
            ]
        ];
        return $behaviors;
    }

    public function actionDecline($id){
        $user = User::findOne($id);
        if(!$user){
            throw new NotFoundHttpException();
        }
        $model = new EmailTasks([
            'email' => $user->email
        ]);

        $model->load(Yii::$app->request->post());
        $model->save(false);
        return $this->redirect('index');
    }

    public function actionLoginUser($id){
        if(!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException();
        }
        if(false == ($user = User::findOne($id))){
            throw new NotFoundHttpException();
        }
        Yii::$app->user->logout();
        Yii::$app->user->login($user);
        return $this->redirect(['/']);
    }

}
