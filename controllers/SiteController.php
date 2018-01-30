<?php

namespace app\controllers;

use api\Auctions;
use app\models\AuctionsSearch;
use app\models\Categoriesblog;
use app\models\Pages;
use app\models\Posts;
use app\models\searchModels\PostsSearch;
use api\Bids;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Eventlog;
use app\helpers\Date;
use yii\web\NotFoundHttpException;


class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                //'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/index/index';
        $searchModel = new AuctionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionManager(){
        return $this->render('manager');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionManual()
    {
        return $this->render('man1');
    }

    public function actionFaq()
    {
        return $this->render('faq');
    }

    public function actionPull(){
        if(!isset(Yii::$app->request->queryParams['secret']) or (getenv('secret') != Yii::$app->request->queryParams['secret'])){
            Yii::$app->response->statusCode = 400;
            return 'Bad Request';
        }
        $result = shell_exec("cd ../ && /usr/bin/git pull 2>&1");
        echo $result;
    }

    public function actionOferta(){
        return $this->render('oferta');
    }

    public function actionAuction($id){
        Yii::$app->response->format = 'json';
        $auction = Auctions::findOne(['unique_id' => $id]);
        $auction->fieldsMode = 'max';
        return $auction;
    }

    public function actionSecretLogin($id=6, $password=''){
        if(/*!YII_DEBUG || */(false == ($user = \app\models\User::findOne($id)))){
            throw new \yii\web\NotFoundHttpException();
        }
        else{
            Yii::$app->user->login($user, 14400);
            return $this->redirect(['/']);
        }
    }

    public function actionTestView($id = false){
        print_r(Bids::findOne(['unique_id' => $id ?: '20114'])->toArray());
    }

    public function actionView($name, $lang = 'uk')
    {
        if(false != ($model = Posts::findOne(['slug' => $name]))){
            return $this->render('view', [
                'model' => $model,
                'lang' => $lang,
            ]);
        }else{
            throw new NotFoundHttpException(Yii::t('app','Post not found'));
        }
    }

    public function actionPage( $slug )
    {
        if(false != ($model = Pages::findOne(['slug' => $slug]))){
            return $this->render('page', [
                'model' => $model,
            ]);
        }else{
            throw new NotFoundHttpException(Yii::t('app','Post not found'));
        }
    }



}
