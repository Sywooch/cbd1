<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\Questions */

$this->title = Yii::t('app', 'Answer the question');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->unique_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="questions-update container">

    <h1><?= Html::encode($this->title) ?></h1>
    <h3><?=$model->title; ?></h3>
    <div class="well">
        <?=$model->description; ?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
