<?php

use yii\helpers\Html;
use yii\grid\GridView;
Use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searchModels\Help */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Helps');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Help'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Go To Video'), ['/help-video/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            //'h1',
            //'short_text:ntext',
            //'text:ntext',
             'description',
             'key_words',
             [
                 'attribute' => 'cat_id',
                 'value' => 'cat.title',
                 'filter'=>Select2::widget([
                     'model' => $searchModel,
                     'attribute' => 'cat_id',
                     'data'=> \yii\helpers\ArrayHelper::map(\app\models\HelpCategory::find()->all(),'id','title'),
                     'options' => [
                         'placeholder' => Yii::t('app','Choose category'),
                         'allowClear' => true,
                     ],
                 ]),
             ],
            // 'slug',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
