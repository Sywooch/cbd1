<?php

namespace app\controllers;

use Yii;
use app\models\Categoriesblog;
use app\models\searchModels\CategoriesblogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\Posts;
use app\models\searchModels\PostsSearch;
use app\models\Pages;

/**
 */
class CategoriesblogController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' =>[
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'category', 'view', 'blog'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'category', 'view', 'blog'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $searchModel = new CategoriesblogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

    public function actionCreate()
    {
        $model = new Categoriesblog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionBlog(){
        $dataProvider = new ActiveDataProvider([
            'query' => Posts::find(),
            'pagination' => [
                'defaultPageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
            'sort' => [
                'defaultOrder'=>[
                    'id' => SORT_DESC,
                ],
            ],
        ]);
        return $this->render('blog', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($slug, $lang = null)
    {
        $query = Categoriesblog::findOne(['slug' => $slug]);
        //метатеги для категорий

        $title = $query->title;
        $key = $query->key_words;
        $desc = $query->description;
        //метатеги для категорий lang = ru
        $title_ru = $query->title_ru;
        $key_ru = $query->key_words_ru;
        $desc_ru = $query->description_ru;
        if($lang == 'ru'){
            $this->view->title = $title_ru;
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => $key_ru]);
            $this->view->registerMetaTag(['name' => 'description', 'content' => $desc_ru]);
        }else {
            $this->view->title = $title;
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => $key]);
            $this->view->registerMetaTag(['name' => 'description', 'content' => $desc]);
        }
        //-----

        $searchModel = new PostsSearch();

        $searchModel->categorySlug = $slug;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, '');

        //if($lang === null) unset($lang);

        return $this->render('blog',[
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider,
            'lang' => $lang,
        ]);


    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Categoriesblog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
