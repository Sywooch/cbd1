<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\ArrayHelper;
use app\models\Categoriesblog;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\searchModels\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Posts'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            //'text:ntext',
            [
                'attribute' => 'Picture',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->getPicture(),['class' => 'img-responsive', 'style' => 'max-width : 150px ']);
                }
            ],

            [
                'attribute' => 'cat_id',
                'value' => 'category.title',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'cat_id',
                    'data' => ArrayHelper::map(Categoriesblog::find()->all(), 'id', 'title'),
                    'options' => [
                        'placeholder' => Yii::t('app','Choose category'),
                        'allowClear' => true,
                    ],
                ])
            ] ,
            'description',
            'key_words',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
