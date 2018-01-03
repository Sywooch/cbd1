<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\Questions */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-view container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($model->answer == ''): ?>
        <p>
            <?= Html::a(Yii::t('app', 'Answer'), ['answer', 'id' => $model->unique_id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'unique_id',
//            'id',
            'author.contactPoint_name',
            'title',
            'description:ntext',
//            'date',
//            'dateAnswered',
            'answer:ntext',
//            'questionOf',
//            'relatedItem',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
