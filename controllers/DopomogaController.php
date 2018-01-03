<?php

namespace app\controllers;

use app\models\HelpCategory;
use app\models\HelpVideo;
use Yii;
use app\models\Help;
use app\models\searchModels\Help as HelpSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * HelpController implements the CRUD actions for Help model.
 */
class DopomogaController extends Controller
{
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
        ];
    }


    /**
     * Lists all Help models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => HelpCategory::find(),
        ]);
        return $this->render('index',[
            'dataProvider' =>$dataProvider,
        ]);
    }

    public function actionCategory($slug)
    {
        $category = HelpCategory::findOne(['slug' => $slug]);
        $videos = HelpVideo::findAll(['cat_id' => $category->id]);

        $title = $category->title;
        $key = $category->key_words;
        $desc = $category->description;
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => $key]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => $desc]);

        $dataProvider = new ActiveDataProvider([
            'query' => Help::find()->where(['cat_id' => $category->id]),
            'pagination' => [
                'defaultPageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('category',[
            'dataProvider' => $dataProvider,
            'videos' => $videos,
        ]);
    }

    public function actionView($slug)
    {
        if (false != ($model = Help::findOne(['slug' => $slug]))) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Post not found'));
        }
    }


}
